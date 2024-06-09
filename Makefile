.PHONY: *

# Set additional parameters for command
OPTS=

list:
	@grep -E '^[a-zA-Z%_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

check: lint-spectral lint-redocly

lint-spectral: ## Validate the OpenAPI specification
	@echo "Linting with Spectral"
	@spectral lint --ruleset doc/.spectral.yaml ${OPTS} doc/openapi.yaml

lint-redocly: ## Validate the OpenAPI specification
	@echo "Linting with redocly/cli"
	@redocly lint --config doc/redocly.yaml ${OPTS} doc/openapi.yaml

doc-build: ## Build the docs
	@bin/build-docs.sh

doc-preview: ## Preview the docs
	@bin/preview-docs.sh
