## Apresentação Geral

**Nome do Projeto:** Classificados Fatec

**Descrição:**

O Classificados Fatec é uma plataforma desenvolvida especialmente para os alunos da Fatec, com o objetivo de facilitar a troca de informações 
e recursos entre a comunidade acadêmica. Este sistema permite que os usuários se cadastrem e criem anúncios relacionados à faculdade e aos 
cursos oferecidos. Os anúncios podem abranger uma variedade de itens, desde monitorias acadêmicas até produtos relevantes para o dia a dia 
do aluno.

Além de cadastrar e visualizar anúncios, os usuários têm a opção de criar pedidos de solicitação para itens ou serviços que não estejam 
disponíveis na plataforma. Isso garante que todas as necessidades da comunidade acadêmica possam ser atendidas de maneira eficiente.

A plataforma também oferece recursos interativos para a avaliação de anúncios. Cada anúncio pode ser comentado e avaliado com estrelas, 
permitindo que outros usuários compartilhem suas experiências e opiniões. Além disso, há uma seção dedicada para dúvidas em cada anúncio, 
promovendo uma comunicação mais eficaz entre os interessados.

O Classificados Fatec é a ferramenta ideal para quem deseja otimizar a troca de informações e a oferta de recursos dentro da faculdade, 
garantindo um ambiente colaborativo e dinâmico para todos os seus usuários.

![demo](./public/assets/img/demo.gif)

**Objetivo:**

Implementar um sistema para o Trabalho de Conclusão de Curso de Análise e Desenvolvimento de Sistemas para a Fatec Sorocaba.

**Tecnologias Utilizadas:**

![DOCKER](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=fff)
![COMPOSER](https://img.shields.io/badge/Composer-885630?style=for-the-badge&logo=composer&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MYSQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![HTML](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![BOOTSTRAP](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)
![JAVASCRIPT](https://img.shields.io/badge/JavaScript-323330?style=for-the-badge&logo=javascript&logoColor=F7DF1E)
![JQUERY](https://img.shields.io/badge/jQuery-0769AD?style=for-the-badge&logo=jquery&logoColor=white)

## Para Desenvolvedores

Se você é um desenvolvedor interessado em contribuir ou entender melhor o funcionamento do projeto, aqui estão algumas informações adicionais:

**Ambiente:**

![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?style=for-the-badge&logo=php)
![MYSQL](https://img.shields.io/badge/MySQL-8.0-005C84?style=for-the-badge&logo=mysql)

```sql
CREATE DATABASE IF NOT EXISTS `classificados_fatec`;

USE `classificados_fatec`;

CREATE TABLE IF NOT EXISTS `user` (
    `user_id` INT NOT NULL AUTO_INCREMENT,
    `admin` BOOLEAN NOT NULL DEFAULT 0,
    `name` VARCHAR(255) NOT NULL,
    `birth_date` DATE NOT NULL,
    `phone` VARCHAR(20),
    `institution` INT NOT NULL,
    `email` VARCHAR(100) UNIQUE NOT NULL,
    `password` VARCHAR(60) NOT NULL,
    `token` VARCHAR(40),
    `active` BOOLEAN NOT NULL DEFAULT 0,
    `last_access` DATETIME,
    PRIMARY KEY(`user_id`)
);

CREATE TABLE IF NOT EXISTS `ad` (
    `ad_id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `status` BOOLEAN NOT NULL DEFAULT 0,
    `ad_date` DATETIME NOT NULL DEFAULT NOW(),
    `price` DECIMAL(10, 2) NOT NULL,
    `discount` DECIMAL(10, 2),
    `discount_date` DATETIME,
    `type` ENUM('monitoria', 'produto') NOT NULL,
    `expiry_date` DATE,
    PRIMARY KEY(`ad_id`),
    FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`)
);

CREATE TABLE IF NOT EXISTS `tutoring` (
    `tutoring_id` INT NOT NULL AUTO_INCREMENT,
    `ad_id` INT NOT NULL,
    `subject` VARCHAR(255) NOT NULL,
    `schedules` JSON NOT NULL,
    PRIMARY KEY(`tutoring_id`),
    FOREIGN KEY (`ad_id`) REFERENCES `ad`(`ad_id`)
);

CREATE TABLE IF NOT EXISTS `product` (
    `product_id` INT NOT NULL AUTO_INCREMENT,
    `ad_id` INT NOT NULL,
    `photo_name` VARCHAR(255),
    `photo_token` VARCHAR(255),
    `condition` ENUM('novo', 'seminovo', 'usado') NOT NULL,
    `operation` ENUM('venda', 'troca', 'ambos') NOT NULL,
    `quantity` INT NOT NULL DEFAULT 0,
    PRIMARY KEY(`product_id`),
    FOREIGN KEY (`ad_id`) REFERENCES `ad`(`ad_id`)
);

CREATE TABLE IF NOT EXISTS `review` (
    `review_id` INT NOT NULL AUTO_INCREMENT,
    `ad_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `comment` TEXT,
    `rating` TINYINT NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
    `review_date` DATETIME NOT NULL DEFAULT NOW(),
    PRIMARY KEY(`review_id`),
    FOREIGN KEY (`ad_id`) REFERENCES `ad`(`ad_id`),
    FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`)
);

CREATE TABLE IF NOT EXISTS `question` (
    `question_id` INT NOT NULL AUTO_INCREMENT,
    `ad_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `question` TEXT NOT NULL,
    `question_date` DATETIME NOT NULL DEFAULT NOW(),
    `answer` TEXT,
    `answer_date` DATETIME,
    PRIMARY KEY(`question_id`),
    FOREIGN KEY (`ad_id`) REFERENCES `ad`(`ad_id`),
    FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`)
);

CREATE TABLE IF NOT EXISTS `solicitation` (
    `solicitation_id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `solicitation_date` DATETIME NOT NULL DEFAULT NOW(),
    `type` ENUM('monitoria', 'produto') NOT NULL,
    `expiry_date` DATE,
    PRIMARY KEY(`solicitation_id`),
    FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`)
);

CREATE TABLE IF NOT EXISTS `comment` (
    `comment_id` INT NOT NULL AUTO_INCREMENT,
    `solicitation_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `comment` TEXT NOT NULL,
    `comment_date` DATETIME NOT NULL DEFAULT NOW(),
    PRIMARY KEY(`comment_id`),
    FOREIGN KEY (`solicitation_id`) REFERENCES `solicitation`(`solicitation_id`),
    FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`)
);
```

**Instruções de Instalação e Configuração:**

> Atenção: Obrigatório o uso de Docker em sua máquina.

1. Clone o repositório do projeto:
```
git clone https://github.com/edssaac/classificadosfatec
```

2. Navegue até o diretório do projeto:
```
cd classificadosfatec
```

3. Inicie a aplicação atráves do script que configura o Docker:
```
.ci_cd/init.sh  
```
Com isso a aplicação estará acessivel: [http://localhost:8080](http://localhost:8080)

---

4. Quando desejar encerrar a aplicação, use:
```
.ci_cd/stop.sh
```
Caso deseje encerrar e remover os volumes criados, use:
```
.ci_cd/stop.sh -v
```

## Contato

[![GitHub](https://img.shields.io/badge/GitHub-100000?style=for-the-badge&logo=github&logoColor=white)](https://github.com/edssaac)
[![Gmail](https://img.shields.io/badge/Gmail-D14836?style=for-the-badge&logo=gmail&logoColor=white)](mailto:edssaac@gmail.com)
[![Outlook](https://img.shields.io/badge/Outlook-0078D4?style=for-the-badge&logo=microsoft-outlook&logoColor=white)](mailto:edssaac@outlook.com)
[![Linkedin](https://img.shields.io/badge/LinkedIn-black.svg?style=for-the-badge&logo=linkedin&color=informational)](https://www.linkedin.com/in/edssaac)
