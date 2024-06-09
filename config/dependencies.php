<?php

declare(strict_types=1);

use App\Middleware\OpenApiValidationMiddleware;
use DI\ContainerBuilder;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Log\LoggerInterface;

return static function (ContainerBuilder $containerBuilder, array $settings) {
    $containerBuilder->addDefinitions([
        'settings' => $settings,

        LoggerInterface::class => static function (ContainerInterface $c) {
            /** @var array $settings */
            $settings = $c->get('settings')['logger'];

            $logger = new Logger($settings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($settings['path'], $settings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },

        Connection::class => static function (ContainerInterface $c) {
            /** @var array{'driver': string} $params */
            $params = $c->get('settings')['db'];

            $connection = DriverManager::getConnection($params);
            if (str_starts_with($params['driver'], 'sqlite')) {
                $connection->executeStatement('PRAGMA foreign_keys = ON');
            }

            return $connection;
        },

        OpenApiValidationMiddleware::class => static function (ContainerInterface $c) {
            $builder = new class extends ValidatorBuilder
            {
                public function getValidationMiddleware(): MiddlewareInterface
                {
                    return new OpenApiValidationMiddleware(
                        $this->getServerRequestValidator(),
                        $this->getResponseValidator()
                    );
                }
            };
            $builder->fromYamlFile(__DIR__ . '/../doc/openapi.yaml');
            //$builder->setCache(...)->overrideCacheKey('openapi');
            $mw = $builder->getValidationMiddleware();
            return $mw;
        }
    ]);
};
