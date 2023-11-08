GENERATED_DOCKER_IMAGES := $(shell docker images | grep "containerizing" | awk '{print $$1}')

svc-up:
	docker compose up -d

svc-down:
	docker compose down -v --remove-orphans

delete-images:
ifneq ($(strip $(GENERATED_DOCKER_IMAGES)),)
	docker rmi -f $(GENERATED_DOCKER_IMAGES)
	@echo "generated docker images succesfully deleted"
else
	@echo "generated docker images are not found"
endif

build-image:
	docker build -f ./docker/Dockerfile.php -t containerizing-laravel:latest .

publish-image:
	docker tag containerizing-laravel:latest us-central1-docker.pkg.dev/workshop-infra-simkes-ugm/simkes-production-registry/containerizing-laravel:latest
	docker push us-central1-docker.pkg.dev/workshop-infra-simkes-ugm/simkes-production-registry/containerizing-laravel:latest
