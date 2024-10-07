.PHONY: rebuild

rebuild:
	docker-compose down
	docker-compose up --build -d
