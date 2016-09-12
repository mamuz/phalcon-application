NAME = $(notdir $(shell pwd))

default: clean build cp-artifacts test

build:
	docker build -t $(NAME) .

cp-artifacts:
	@docker create --name $(NAME) $(NAME)
	@docker cp $(NAME):/phapp/vendor ./
	@docker rm -fv $(NAME)

clean:
	-@docker rm -fv $(NAME)
	-@docker rmi -f $(NAME)
	-@rm -rf vendor

test:
	@docker run --rm -it -v $(shell pwd):/phapp $(NAME) ./vendor/bin/codecept run -vvv
