# User Wallet and Transaction Management API

## Overview
This project provides a RESTful API built with **Laravel 11** that manages user wallets and transactions. It includes functionalities such as creating users with initial balances, depositing and withdrawing funds, transferring funds between users, and listing user transactions.

## Features
- **Users API**
    - Create a user with an initial balance
    - Get user details with the current balance

- **Wallet API**
    - Deposit funds into a user's wallet
    - Withdraw funds from a user's wallet

- **Transactions API**
    - Transfer funds between users
    - Retrieve a user's transaction history

## Technologies Used
- **Backend**: Laravel 11, PHP 8.2
- **Database**: MySQL
- **API**: RESTful API with proper error handling and validation (postman collection added)
- **Validation**: Laravel's built-in validation and custom error messages
- **Transactions**: Database transactions for secure fund transfer operations
- **Authentication**: None (public API)

## Installation

### Step 1: Clone the Repository
```bash
git clone https://github.com/usmanbutt836/Simple-Wallet-Management-API.git
