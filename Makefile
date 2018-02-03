.PHONY: analyse

analyse:
	sonar-scanner \
  		-Dsonar.projectKey=tapping-library \
  		-Dsonar.organization=troublete-github \
  		-Dsonar.sources=. \
  		-Dsonar.host.url=https://sonarcloud.io \
  		-Dsonar.login=ae8283376ea0690d88a5c83cb83b423acbd218fd \
		-Dsonar.exclusions=vendor/**,test/**	
