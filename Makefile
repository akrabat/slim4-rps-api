.PHONY: *

# Set additional parameters for command
OPTS=

list:
	@grep -E '^[a-zA-Z%_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

validate-spec: ## Validate the OpenAPI specification
	docker run --rm -it -v ./doc:/doc stoplight/spectral lint --ruleset /doc/.spectral.yaml ${OPTS} /doc/openapi.yaml
