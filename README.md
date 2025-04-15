
# GSB Project

## Project Context

This project is part of the BTS SIO SLAM option (Software Solutions and Business Applications) E6 assessment. The Appli-Frais application is designed for the pharmaceutical laboratory Galaxy Swiss Bourdin (GSB) and aims to standardize and secure the expense management process for medical representatives.

## Main Objectives

- **Standardize expense management** for medical representatives.
- **Develop a secure web application** to replace existing systems (paper-based and outdated software).
- **Ensure accurate tracking** of reimbursements and compliance with expense regulations.

## Key Features

### For Medical Representatives:

- Secure authentication into the application.
- Monthly entry and modification of expenses (standardized and non-standardized).
- Viewing reimbursement statuses over a one-year period.

### For Accounting Department:

- Monthly validation of expenses submitted by representatives.
- Modification and removal of invalid expenses.
- Payment processing and reimbursement tracking.

## Technologies Used

- **Framework:** Symfony
- **Architecture:** MVC (Model-View-Controller)
- **Language:** PHP
- **Database:** MySQL (or other relational databases)

## Installation and Setup

1. **Clone the repository:**

   ```bash
   git clone https://github.com/Bili-and-sheep/GSB.git
   cd GSB
   ```
   
2. **Configure environment variables:**

   ```bash
   nano .env.local
   ```

3. **Install dependencies:**

   ```bash
   composer install
   ```

4. **Set up the database:**

   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

5. **Start the server:**

   ```bash
   symfony serve
   ```

Access: [http://localhost:8000](http://localhost:8000)

## Project Structure

```
GSB-AppliFrais/
├── config/           # Symfony configuration
├── src/              # Controllers, entities, and business logic
├── templates/        # Twig views
├── migrations/       # Doctrine migrations
├── public/           # Public resources
├── tests/            # Unit tests
└── assets/           # Frontend assets
```

## Database structure:
![GSB data base structure](public/GSB_DDC.png)


## Testing

Run unit and functional tests:

```bash
php bin/phpunit
```

## Maintenance

- **Corrective:** Fix reported bugs.
- **Adaptive:** Integrate improvements based on user feedback.

## Security and Access

- Secure authentication.
- Role-based access control (Representative / Accountant).


---
