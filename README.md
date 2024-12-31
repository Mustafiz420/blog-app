# Blog Application with Docker and CI/CD

## Overview
This project is a Laravel-based blog application that leverages Docker for easy environment setup and GitHub Actions for CI/CD integration. It includes:

- A PHP application running on Apache
- A MySQL database
- SSL certificate handling with Certbot
- Optionally, an Nginx reverse proxy for HTTP to HTTPS redirection

---

## Prerequisites

Ensure the following tools are installed on your system:

- **Docker:** [Installation Guide](https://www.docker.com/products/docker-desktop)
- **Docker Compose:** Typically included with Docker Desktop

---

## Running the Application Locally

### Clone the Repository:
```bash
https://github.com/Mustafiz420/blog-app.git
cd blog-app
```

### Set Up the Environment:
1. Copy the example environment configuration:
   ```bash
   cp .env.example .env
   ```
2. Open the `.env` file and modify the necessary keys, especially database credentials, if required.

### Run Docker Compose:

Build and start the containers:
```bash
docker-compose up --build
```
This command will start all services defined in your `docker-compose.yml`, including PHP (with Apache), MySQL, Certbot, and optionally Nginx.

### Access the Application:
- **HTTP:** Visit `http://localhost:80`
- **HTTPS:** Visit `https://localhost:443` (if SSL is configured with a valid domain instead of localhost)

---

## CI/CD Setup

The CI/CD pipeline is set up using GitHub Actions to automate building, testing, and deploying the application.

### Workflow Steps:

#### 1. **Building and Testing:**
Upon a push or pull request to the `main` branch, the following steps are executed:
- **Check Out Repository:** Clone the repository to the runner.
- **Set Up Docker Buildx:** Ensure Docker is ready to build images.
- **Cache Docker Layers:** Save and restore the build cache to speed up subsequent builds.
- **Build Docker Image:** Compose and build services defined in `docker-compose.yml`.
- **Run Tests:** Execute Laravel tests within the app container.

#### 2. **Deployment:**
If the build succeeds, the deploy job is triggered:
- **Set Up SSH for Secure Deployment:** Access the remote server using SSH keys stored in GitHub Secrets.
- **Deploy to DigitalOcean Droplet:**
  - Connect to your server and deploy with Docker Compose.
  - Ensure your server (e.g., DigitalOcean Droplet) is configured to run Docker and has the necessary file permissions and SSH access.
  - Adjust the server IP and path in the deploy job as required.

---

## Security Considerations
- Configure your `.env` file securely and ensure it is not shared or leaked.
- Use strong secrets and consider rotating them regularly.
- For production, ensure SSL certificates are correctly configured with real domains.

---

## Optional Enhancements
- **Alerts:** Integrate alerts for CI/CD status using tools like Slack or email notifications.
- **Advanced Testing:** Implement integration or end-to-end tests.
- **Monitoring:** Utilize monitoring tools to track application performance in production.

---

## Notes
- **Adapt Environment:**
  - Instructions assume the usage of `localhost`.
  - If deploying, ensure domain names and SSL certificates are correctly configured.

- **Secrets Management:**
  - Store sensitive information like SSH keys and Docker Hub credentials in GitHub Actions Secrets for security.

- **SSH Configuration:**
  - Adjust SSH configurations as necessary to match your infrastructure and security protocols.

