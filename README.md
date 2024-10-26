# Laravel Project with Sanctum Authentication and API Resources

This project is a Laravel-based application featuring user authentication with Sanctum, and API resources for tags and posts. The project includes a verification system, scheduled jobs, and an API stats endpoint.

## Table of Contents
- [Project Setup](#project-setup)
- [Postman Collection for Testing and Documentation](#postman-collection-for-testing-and-documentation)
- [Features](#features)
- [Contributing](#contributing)

---

## Project Setup

1. **Clone the repository**

   ```bash
   git clone https://github.com/sowidan1/Laravel-Verification-API.git
   
2. **Open Project**

   ```bash
   cd Laravel-Verification-API
   ```
   ```bash
   code .
   
3. **Install dependencies**

   ```bash
   composer install

4. **Set up environment variables Copy .env.example to .env and configure it:**

   ```bash
   cp .env.example .env

5. **Generate application key**

   ```bash
   php artisan key:generate

6. **Set up SQLite database in .env file**

7. **Run migrations**

   ```bash
   php artisan migrate

## My OTP Functionality Setup


> **Note**: The OTP functionality relies on [Infobip](https://www.infobip.com/) for sending SMS. You will need valid Infobip credentials to fully test this feature. Infobip offers a free trial for testing purposes, but OTPs will only be sent to the registered phone number used during account setup.

# .env file

```bash

INFOBIP_API_KEY=fb64248cac4a1b924e525075215019b6-5d77bb6d-97be-4242-833a-997ab4382096
INFOBIP_BASE_URL=https://e5eyqr.api.infobip.com
INFOBIP_FROM_NUMBER=447491163443
```

This setup will only send messages to the specific phone number registered for testing with Infobip (in this case, 01019465724).


## Skipping OTP Activation

To bypass OTP activation during development or testing, you can use the following steps:

1. Add these values to your `.env` file to configure Infobip:

    ```bash

    INFOBIP_API_KEY=fb64248cac4a1b924e525075215019b6-5d77bb6d-97be-4242-833a-997ab4382096
    INFOBIP_BASE_URL=https://e5eyqr.api.infobip.com
    INFOBIP_FROM_NUMBER=447491163443
    ```

2. In `App/Http/Services/AuthService.php`, uncomment out line 67 to disable the verification requirement:

    ```php
    // $user->update(['is_verified' => User::IS_VERIFIED]);
    ```

This setup allows you to proceed without OTP verification, which is useful for initial testing and development purposes.


## Postman Collection for Testing and Documentation

You can test the API using the following Postman collection:

1. **Import the Collection**:
   - Open Postman.
   - Click on **Import** in the top left corner.
   - Select **Import From Link**.
   - Paste the following URL:
     
     ```
     https://api.postman.com/collections/27167134-da9f6510-295f-4b03-bfcc-3c680c50f2ed?access_key=PMAT-01JB2WZ3KADBBA05BAK2QTESRC
     ```

## Features

### Authentication System (Sanctum)
- **Register (/register)**: 
  - Registers users with name, phone, and password.
  - A 6-digit verification code is generated and logged.

- **Login (/login)**: 
  - Authenticates verified users and returns user data with an access token.

- **Verify OTP**: 
  - Verifies the OTP sent to the user’s phone.
  - Only verified accounts can log in.

### Tags API Resource
- Authenticated users can:
  - **View** all tags.
  - **Create** new tags.
  - **Update** existing tags.
  - **Delete** tags.
- Tags must have unique names.

### Posts API Resource
- Authenticated users can manage their posts:
  - **View** only their posts.
  - **Create** new posts.
  - **View** a single post.
  - **Update** their posts.
  - **Soft-delete** posts.
  - **View** deleted posts.
  - **Restore** deleted posts.
- Posts include the following fields:
  - Title (required, max 255 characters)
  - Body (required, string)
  - Cover image (required for storing, optional for updating, image type)
  - Pinned status (required, boolean)
  - Associated tags (many-to-many relationship)
- Soft-deleted posts older than 30 days are permanently deleted by a daily scheduled job.

### Scheduled Jobs
- **Daily Cleanup**: Force-deletes soft-deleted posts older than 30 days.
- **External API Call**: Fetches data from [Random User API](https://randomuser.me/api/) every six hours and logs the response.

### Stats Endpoint (/stats)
- Returns:
  - Total count of all users.
  - Total count of all posts.
  - Count of users with no posts.
- The results are cached and update automatically on changes to user and post models.

## Contributing

Contributions are welcome! If you have suggestions for improvements, please feel free to fork the repository and submit a pull request. You can also open an issue if you encounter any bugs or have feature requests.

Please ensure to follow the code of conduct and the contribution guidelines outlined in the repository.
