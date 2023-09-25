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

[◄ Return to the first page](../../Readme.md)
|
[Containers available ►](../misc/containers.md)