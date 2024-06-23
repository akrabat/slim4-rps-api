.PHONY: *

# Set additional parameters for command
OPTS=

list:
	@grep -E '^[a-zA-Z%_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

up: ## Run the API
	php -d html_errors=0 -S 0.0.0.0:8888 -t public/

check: lint-spectral lint-redocly

lint-spectral: ## Validate the OpenAPI specification
	@echo "Linting with Spectral"
	spectral lint --ruleset doc/.spectral.yaml ${OPTS} doc/openapi.yaml

lint-redocly: ## Validate the OpenAPI specification
	@echo "Linting with redocly/cli"
	redocly lint --config doc/redocly.yaml ${OPTS} doc/openapi.yaml

docs-build: ## Build the docs
	@echo "Building docs with redocly/cli"
	redocly build-docs -o doc/rps.html doc/openapi.yaml

docs-preview: ## Preview the docs
	@echo "Preview docs with redocly/cli"
	redocly preview-docs doc/openapi.yaml

mock: ## Mock the API
	prism mock doc/openapi.yaml
