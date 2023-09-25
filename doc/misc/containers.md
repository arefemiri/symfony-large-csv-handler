# Containers available

List of available **Docker** containers.

---

| Nom du conteneur | Image                                                                                                                                                                   | Version  | Ports                                      |
|------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------|----------|--------------------------------------------|
| `web`            | [`Dockerfile`](Dockerfile)                                                                                                                                              | `8.2`    | `8001:80`                                  |
| `database`       | [`mariadb:10.9.4`](https://hub.docker.com/layers/library/mariadb/10.9.4/images/sha256-5834aa3731eda1ab657a3e91370895b3b6fe98b1e720c0b1167877f714345cd3?context=explore) | `10.9.4` | `3306:3306`                                |
| `phpmyadmin`     | [`phpmyadmin`](https://hub.docker.com/_/phpmyadmin)                                                                                                                     | `latest` | `8080:80`                                  | | `latest` | `1080:1080` (WEB) <br/> `1025:1025` (SMTP) |

--- 

[◄ Local Installation](../installation/installation.md)
|
[Thoughts ►](thoughts.md)