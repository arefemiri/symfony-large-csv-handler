# Local Installation

Installation of the **Docker** project for **LBX** task locally.

--- 

## Table of Contents
<!-- TOC -->
* [Local Installation](#local-installation)
  * [Table des matières](#table-of-contents)
  * [Getting the Project Locally](#getting-the-project-locally)
  * [Creating Necessary Files](#creating-necessary-files)
  * [Launching Docker](#launching-docker)
  * [Installing Symfony Dependencies](#installing-symfony-dependencies)
  * [Testing CSV Import](#testing-csv-import)
<!-- TOC -->


## Getting the Project Locally
- Clone the git repository to your machine
```shell
git clone https://github.com/arefemiri/symfony-large-csv-handler.git
```
- Navigate to the project directory
```shell
cd symfony-large-csv-handler
```

## Creating Necessary Files
- Create a .env file with the structure of the .env.example file and fill in the credentials
```shell
cp .env.example .env
```

## Launching Docker
- Start the different containers using `docker-compose` and build the image `Dockerfile`
```shell
docker compose up --build -d
```

## Installing Symfony Dependencies
- Connect to the Docker container named "web"
```shell
docker ps 

docker exec -it [ID_DU_CONTENEUR] zsh
```
- Install the dependencies
```shell
composer install
```

## Testing CSV Import
- To test the CSV import functionality, please follow these steps:
1. Place the CSV file in the project's root directory.
2. Execute the following command to initiate the CSV import:
```shell
curl -X POST -H 'Content-Type: multipart/form-data' -F "file=@import.csv" http://127.0.0.1:8001/api/employee
```

[◄ Return to the first page](../../Readme.md)
|
[Containers available ►](../misc/containers.md)
