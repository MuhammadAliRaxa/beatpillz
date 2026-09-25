# BeatPillz Mobile API Specification (v1)

> **API Version**: 1.0  
> **Backend Framework**: Laravel 10.x / Laravel Sanctum  
> **Base URL (Production)**: `https://beatpillz.com/api/v1`  
> **Base URL (Local)**: `http://127.0.0.1:8000/api/v1`  
> **Base URL (Android Emulator)**: `http://10.0.2.2:8000/api/v1`  
> **Auth Type**: Bearer Token (`Authorization: Bearer <access_token>`)  
> **Format**: `application/json` (except file upload endpoints which use `multipart/form-data`)

---

## Table of Contents
1. [General Standards & Headers](#1-general-standards--headers)
2. [Global App Configuration](#2-global-app-configuration)
3. [Authentication & Social Sign-In](#3-authentication--social-sign-in)
4. [User Profile, KYC & Creator Setup](#4-user-profile-kyc--creator-setup)
5. [Home Discovery, Categories & Producers](#5-home-discovery-categories--producers)
6. [Beats Marketplace & Audio Streaming](#6-beats-marketplace--audio-streaming)
7. [Reviews, Comments & Wishlist](#7-reviews-comments--wishlist)
8. [Shopping Cart & Multi-License](#8-shopping-cart--multi-license)
9. [Mobile Checkout & 1-Click Wallet Payments](#9-mobile-checkout--1-click-wallet-payments)
10. [User Library, Downloads & Statements](#10-user-library-downloads--statements)
11. [Author / Producer Studio](#11-author--producer-studio)
12. [Subscriptions & Premium Plans](#12-subscriptions--premium-plans)
13. [Refund Requests](#13-refund-requests)
14. [Support Tickets & FAQs](#14-support-tickets--faqs)
15. [Blog & News](#15-blog--news)
16. [Flutter / Dart HTTP Service Boilerplate](#16-flutter--dart-http-service-boilerplate)

---

## 1. General Standards & Headers

### Request Headers
For all JSON endpoints:
```http
Accept: application/json
Content-Type: application/json
Authorization: Bearer <access_token>   // When authentication is required
```

For file upload endpoints (`POST /user/avatar`, `POST /author/items/upload`):
```http
Accept: application/json
Content-Type: multipart/form-data
Authorization: Bearer <access_token>
```

### Standard Response Envelope
#### Success Response (`200 OK` / `201 Created`)
```json
{
  "success": true,
  "message": "Operation successful.",
  "data": { ... }
}
```

#### Validation Error (`422 Unprocessable Entity`)
```json
{
  "success": false,
  "message": "The email has already been taken.",
  "errors": {
    "email": ["The email has already been taken."]
  }
}
```

#### Unauthenticated (`401 Unauthorized`)
```json
{
  "message": "Unauthenticated."
}
```

#### Forbidden (`403 Forbidden`)
```json
{
  "success": false,
  "message": "Your account is blocked. Please contact support."
}
```

---

## 2. Global App Configuration

### `GET /config`
* **Auth**: Public
* **Description**: Bootstraps mobile app settings, currencies, exchange rates, legal links, and contact information.
* **Response `200 OK`**:
```json
{
  "success": true,
  "config": {
    "site_name": "Beat Pillz",
    "site_url": "https://beatpillz.com",
    "default_currency": {
      "code": "USD",
      "symbol": "$",
      "position": 1,
      "rate": 1.0
    },
    "currencies": [
      {
        "code": "USD",
        "symbol": "$",
        "position": 1,
        "rate": 1.0,
        "icon": "https://beatpillz.com/assets/images/currencies/usd.png"
      }
    ],
    "legal_links": {
      "terms_of_use": "https://beatpillz.com/terms-of-use",
      "privacy_policy": "https://beatpillz.com/privacy-policy",
      "refund_policy": "https://beatpillz.com/refund-policy"
    },
    "contact": {
      "email": "support@beatpillz.com",
      "phone": "+1234567890"
    }
  }
}
### `GET /currencies`
* **Auth**: Public
* **Description**: Returns all supported currencies, their exchange conversion rates, currency symbols, and the platform default currency.
* **Response `200 OK`**:
```json
{
  "success": true,
  "default_currency": {
    "code": "USD",
    "symbol": "$",
    "position": 1,
    "rate": 1.0
  },
  "currencies": [
    {
      "code": "USD",
      "symbol": "$",
      "position": 1,
      "position_name": "Before price",
      "rate": 1.0,
      "icon": "https://beatpillz.com/assets/images/currencies/usd.png",
      "is_default": true
    },
    {
      "code": "NGN",
      "symbol": "₦",
      "position": 1,
      "position_name": "Before price",
      "rate": 1500.0,
      "icon": null,
      "is_default": false
    }
  ]
}
```

---

## 3. Authentication & Social Sign-In

### `POST /auth/register`
* **Auth**: Public
* **Description**: Create a new listener/buyer account.
* **Payload**:
```json
{
  "firstname": "John",
  "lastname": "Doe",
  "username": "johndoe",
  "email": "john@example.com",
  "password": "Password123",
  "password_confirmation": "Password123",
  "device_name": "iPhone 15 Pro"
}
```
* **Validation Rules**:
  * `firstname`: required, string, max:50
  * `lastname`: required, string, max:50
  * `username`: required, string, min:6, max:50, alpha_dash, unique:users
  * `email`: required, email, max:100, unique:users
  * `password`: required, min:8, confirmed
  * `device_name`: nullable, string, max:100
* **Response `201 Created`**:
```json
{
  "success": true,
  "message": "Registration successful.",
  "access_token": "1|qWeRtYuIoP123...",
  "token_type": "Bearer",
  "user": {
    "id": 10,
    "firstname": "John",
    "lastname": "Doe",
    "fullname": "John Doe",
    "username": "johndoe",
    "email": "john@example.com",
    "avatar": null,
    "is_author": false,
    "balance": 0.00,
    "currency": "USD"
  }
}
```

---

### `POST /auth/login`
* **Auth**: Public
* **Description**: Authenticate with either **Email** or **Username**.
* **Payload**:
```json
{
  "login": "john@example.com", // OR "johndoe"
  "password": "Password123",
  "device_name": "Pixel 8 Pro"
}
```
* **Validation Rules**:
  * `login`: required, string
  * `password`: required, string
  * `device_name`: nullable, string, max:100
* **Response `200 OK` (Standard Login)**:
```json
{
  "success": true,
  "message": "Login successful.",
  "access_token": "2|aBcDeFgHiJ456...",
  "token_type": "Bearer",
  "user": { ... }
}
```

* **Response `200 OK` (When 2FA is Enabled on Account)**:
```json
{
  "success": true,
  "requires_2fa": true,
  "two_factor_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "message": "2FA authentication code required."
}
```

---

### `POST /auth/2fa/verify`
* **Auth**: Public (Pass `two_factor_token` from login response)
* **Description**: Verify the 6-digit TOTP Authenticator code and complete login.
* **Payload**:
```json
{
  "two_factor_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "otp_code": "123456",
  "device_name": "Pixel 8 Pro"
}
```
* **Response `200 OK`**:
```json
{
  "success": true,
  "message": "Login successful.",
  "access_token": "2|aBcDeFgHiJ456...",
  "token_type": "Bearer",
  "user": { ... }
}
```

---

### `POST /auth/social-login`
* **Auth**: Public
* **Description**: Authenticate via mobile native Google, Apple, or Facebook SDK tokens. Creates account automatically if not registered.
* **Payload**:
```json
{
  "provider": "google", // "google" | "apple" | "facebook"
  "provider_id": "1098239048209384",
  "email": "john@example.com",
  "firstname": "John",
  "lastname": "Doe",
  "avatar": "https://lh3.googleusercontent.com/a/...",
  "device_name": "Flutter Client"
}
```
* **Response `200 OK`**:
```json
{
  "success": true,
  "message": "Social login successful.",
  "access_token": "3|zXcVbNmLk789...",
  "token_type": "Bearer",
  "user": { ... }
}
```

---

### `POST /auth/forgot-password`
* **Auth**: Public
* **Payload**:
```json
{
  "email": "john@example.com"
}
```
* **Response `200 OK`**:
```json
{
  "success": true,
  "message": "Password reset instructions have been sent to your email address."
}
```

---

### `POST /auth/logout`
* **Auth**: Bearer Token Required
* **Description**: Revokes the caller's active device token.
* **Response `200 OK`**:
```json
{
  "success": true,
  "message": "Logged out successfully."
}
```

---

## 4. User Profile, KYC & Creator Setup

### `GET /user/profile`
* **Auth**: Bearer Token Required
* **Response `200 OK`**: Returns full authenticated `UserResource`.

---

### `PUT /user/profile`
* **Auth**: Bearer Token Required
* **Payload**:
```json
{
  "firstname": "John",
  "lastname": "Doe",
  "profile_heading": "Producer & Audio Engineer",
  "profile_description": "Specialized in 808s, melodies and boom bap.",
  "social_links": {
    "instagram": "https://instagram.com/johndoe",
    "youtube": "https://youtube.com/@johndoe",
    "twitter": "https://x.com/johndoe"
  }
}
```
* **Response `200 OK`**:
```json
{
  "success": true,
  "message": "Profile updated successfully.",
  "user": { ... }
}
```

---

### `PUT /user/account-details`
* **Auth**: Bearer Token Required
* **Description**: Updates user's personal account and billing address details (matching the web settings screen).
* **Payload**:
```json
{
  "firstname": "Favour",
  "lastname": "Ebuka",
  "email": "beatpillz.ng@gmail.com",
  "address_line_1": "123 water park view",
  "address_line_2": null,
  "city": "Lagos",
  "state": "Lagos",
  "zip": "234001",
  "country": "NG",
  "exclusivity": "exclusive"
}
```
* **Response `200 OK`**:
```json
{
  "success": true,
  "message": "Account details updated successfully.",
  "user": {
    "id": 10,
    "firstname": "Favour",
    "lastname": "Ebuka",
    "fullname": "Favour Ebuka",
    "username": "ebukabeats",
    "email": "beatpillz.ng@gmail.com",
    "is_author": true,
    "exclusivity": "exclusive",
    "address": {
      "line_1": "123 water park view",
      "line_2": null,
      "city": "Lagos",
      "state": "Lagos",
      "zip": "234001",
      "country": "NG",
      "country_name": "Nigeria"
    }
  }
}
```

---

### `POST /user/avatar`
* **Auth**: Bearer Token Required
* **Content-Type**: `multipart/form-data`
* **Fields**:
  * `avatar`: image file (`jpeg, png, jpg, webp`, max: 4096 KB)
* **Response `200 OK`**:
```json
{
  "success": true,
  "message": "Avatar updated successfully.",
  "avatar": "https://beatpillz.com/storage/images/avatars/avatar_10_1725350000.jpg",
  "user": { ... }
}
```

---

### `PUT /user/password`
* **Auth**: Bearer Token Required
* **Payload**:
```json
{
  "current_password": "OldPassword123",
  "password": "NewPassword123",
  "password_confirmation": "NewPassword123"
}
```
* **Response `200 OK`**:
```json
{
  "success": true,
  "message": "Password changed successfully."
}
```

---

### `GET /user/2fa`
* **Auth**: Bearer Token Required
* **Description**: Retrieve two-factor authentication status and setup QR code / manual key data.
* **Response `200 OK` (2FA Disabled - Setup Details)**:
```json
{
  "success": true,
  "is_enabled": false,
  "setup": {
    "secret_key": "JBSWY3DPEHPK3PXP",
    "qr_code_url": "data:image/svg+xml;base64,PHN2ZyB4bWxucz0...",
    "otpauth_url": "otpauth://totp/Beat%20Pillz:john@example.com?secret=JBSWY3DPEHPK3PXP&issuer=Beat+Pillz"
  }
}
```
* **Response `200 OK` (2FA Already Enabled)**:
```json
{
  "success": true,
  "is_enabled": true,
  "setup": null
}
```

---

### `POST /user/2fa/enable`
* **Auth**: Bearer Token Required
* **Description**: Enable 2FA authentication by verifying a 6-digit code from Google Authenticator.
* **Payload**:
```json
{
  "otp_code": "123456"
}
```
* **Response `200 OK`**:
```json
{
  "success": true,
  "message": "2FA Authentication has been enabled successfully.",
  "is_enabled": true
}
```
* **Error Response `422 Unprocessable`**:
```json
{
  "success": false,
  "message": "Invalid OTP code. Please check your Authenticator app and try again."
}
```

---

### `POST /user/2fa/disable`
* **Auth**: Bearer Token Required
* **Description**: Disable 2FA authentication by confirming with a 6-digit code.
* **Payload**:
```json
{
  "otp_code": "123456"
}
```
* **Response `200 OK`**:
```json
{
  "success": true,
  "message": "2FA Authentication has been disabled successfully.",
  "is_enabled": false
}
```

---

### `GET /user/kyc`
* **Auth**: Bearer Token Required
* **Description**: Retrieves current KYC verification status (`0: Unverified`, `1: Pending`, `2: Verified`, `3: Rejected`), requirements & guidance sample images, and latest submission details.
* **Image URL Handling**:
  * **Sample/Guidance Images** (`settings.sample_images.*`): Hosted publicly on the server; URLs are generated via `asset()` and can be displayed directly in mobile apps without headers.
  * **Uploaded Documents** (`submission.documents.*.url`): Government IDs are stored privately in server storage (`storage/app/kyc/docs/...`). Secure streaming URLs pointing to `/api/v1/user/kyc/documents/{document}` are provided; mobile apps must pass `Authorization: Bearer <token>` when requesting these files.
* **Response `200 OK`**:
```json
{
  "success": true,
  "kyc_status": 1,
  "is_verified": false,
  "is_pending": true,
  "is_required": false,
  "settings": {
    "is_enabled": true,
    "is_required": false,
    "selfie_verification": true,
    "supported_document_types": {
      "national_id": "National ID",
      "passport": "Passport"
    },
    "sample_images": {
      "id_front_image": "https://api.beatpillz.com/images/kyc/64e83f218a...png",
      "id_back_image": "https://api.beatpillz.com/images/kyc/64e83f219b...png",
      "passport_image": "https://api.beatpillz.com/images/kyc/64e83f21ac...png",
      "selfie_image": "https://api.beatpillz.com/images/kyc/64e83f21bd...png"
    }
  },
  "submission": {
    "id": 1,
    "document_type": "national_id",
    "document_type_name": "National ID",
    "document_number": "12345-6789012-3",
    "status": 1,
    "status_name": "Pending",
    "rejection_reason": null,
    "documents": {
      "front_of_id": {
        "path": "kyc/docs/a1b2c3d4/front_1692800000.jpg",
        "url": "https://api.beatpillz.com/api/v1/user/kyc/documents/front_of_id"
      },
      "back_of_id": {
        "path": "kyc/docs/a1b2c3d4/back_1692800000.jpg",
        "url": "https://api.beatpillz.com/api/v1/user/kyc/documents/back_of_id"
      },
      "selfie": {
        "path": "kyc/docs/a1b2c3d4/selfie_1692800000.jpg",
        "url": "https://api.beatpillz.com/api/v1/user/kyc/documents/selfie"
      }
    },
    "created_at": "2026-02-01T10:00:00.000000Z",
    "updated_at": "2026-02-02T14:30:00.000000Z"
  }
}
```

---

### `POST /user/kyc`
* **Auth**: Bearer Token Required
* **Content-Type**: `multipart/form-data`
* **Description**: Submit KYC verification documents and identification numbers (same validation and storage logic as web).
* **Parameters**:
  * `document_type` (*required*): string (`national_id` or `passport`)
  * **When `document_type` is `national_id`**:
    * `front_of_id` (*required*): image file (`jpeg, jpg, png`, max 4096 KB)
    * `back_of_id` (*required*): image file (`jpeg, jpg, png`, max 4096 KB)
    * `national_id_number` (*required*): string (max 30 chars)
  * **When `document_type` is `passport`**:
    * `passport` (*required*): image file (`jpeg, jpg, png`, max 4096 KB)
    * `passport_number` (*required*): string (max 30 chars)
  * **When Selfie Verification is enabled**:
    * `selfie` (*required*): image file (`jpeg, jpg, png`, max 4096 KB)
* **Response `201 Created`**:
```json
{
  "success": true,
  "message": "Your documents have been submitted successfully and are pending review.",
  "kyc_status": 1,
  "submission": {
    "id": 2,
    "document_type": "national_id",
    "document_type_name": "National ID",
    "document_number": "12345-6789012-3",
    "status": 1,
    "status_name": "Pending",
    "documents": {
      "front_of_id": {
        "path": "kyc/docs/a1b2c3d4/front_1692800000.jpg",
        "url": "https://api.beatpillz.com/api/v1/user/kyc/documents/front_of_id"
      },
      "back_of_id": {
        "path": "kyc/docs/a1b2c3d4/back_1692800000.jpg",
        "url": "https://api.beatpillz.com/api/v1/user/kyc/documents/back_of_id"
      },
      "selfie": {
        "path": "kyc/docs/a1b2c3d4/selfie_1692800000.jpg",
        "url": "https://api.beatpillz.com/api/v1/user/kyc/documents/selfie"
      }
    },
    "created_at": "2026-09-14T10:50:00.000000Z",
    "updated_at": "2026-09-14T10:50:00.000000Z"
  }
}
```

---

### `GET /user/kyc/documents/{document}`
* **Auth**: Bearer Token Required
* **Description**: Securely stream and view a submitted KYC document file for the authenticated user (`front_of_id`, `back_of_id`, `passport`, `selfie`).
* **Path Parameter**:
  * `document`: `front_of_id` | `back_of_id` | `passport` | `selfie`
* **Response `200 OK`**: Binary image stream with correct MIME type (e.g., `image/jpeg`, `image/png`).

---

### `GET /user/badges`
* **Auth**: Bearer Token Required
* **Description**: Retrieves all badges earned by the authenticated user in custom display order (`sort_id` ASC), identical to web workspace settings (`/workspace/settings/badges`).
* **Response `200 OK`**:
```json
{
  "success": true,
  "count": 3,
  "badges": [
    {
      "id": 12,
      "badge_id": 1,
      "name": "Verified Account",
      "alias": "verified_account",
      "title": "Identity Verified",
      "full_title": "Verified Account: Identity Verified",
      "image": "https://api.beatpillz.com/images/badges/verified.png",
      "country": null,
      "sort_id": 1
    },
    {
      "id": 15,
      "badge_id": 5,
      "name": "Level 1",
      "alias": "author_level",
      "title": "Beginner Producer",
      "full_title": "Level 1: Beginner Producer",
      "image": "https://api.beatpillz.com/images/badges/level1.png",
      "country": null,
      "sort_id": 2
    },
    {
      "id": 18,
      "badge_id": 9,
      "name": "United States",
      "alias": "country",
      "title": null,
      "full_title": "United States",
      "image": "https://api.beatpillz.com/images/badges/us.png",
      "country": "US",
      "sort_id": 3
    }
  ]
}
```

---

### `POST /user/badges/sortable`
* **Auth**: Bearer Token Required
* **Description**: Reorders user's badges (identical to web workspace drag-and-drop sortable action).
* **Payload**:
```json
{
  "ids": [15, 12, 18]
}
```
*(Also accepts comma-separated string: `{"ids": "15,12,18"}`)*
* **Response `200 OK`**:
```json
{
  "success": true,
  "message": "Badges sorted successfully.",
  "badges": [ ... ]
}
```

---

### `GET /producers/{username_or_id}/badges`
* **Auth**: None (Public)
* **Description**: Retrieves public badges earned by a specific producer/user.
* **Response `200 OK`**: Same schema as `GET /user/badges`.

---

### `POST /user/become-author`
* **Auth**: Bearer Token Required
* **Description**: Upgrades a listener account to an Author / Beatmaker account and assigns the default Level Badge.
* **Response `200 OK`**:
```json
{
  "success": true,
  "message": "Congratulations! You are now an author.",
  "user": {
    "id": 10,
    "is_author": true,
    ...
  }
}
```

---

### `PUT /user/withdrawal-account`
* **Auth**: Bearer Token Required
* **Description**: Sets the producer's payout account (e.g. PayPal email or Bank account).
* **Payload**:
```json
{
  "withdrawal_method_id": 1,
  "withdrawal_account": "payouts@mybeats.com"
}
```
* **Response `200 OK`**:
```json
{
  "success": true,
  "message": "Withdrawal account updated successfully.",
  "user": { ... }
}
```

---

### `GET /user/api-key`
* **Auth**: Bearer Token Required
* **Description**: Retrieves the user's developer API key and system API status (identical to web workspace `/workspace/settings/api-key`).
* **Response `200 OK`**:
```json
{
  "success": true,
  "is_enabled": true,
  "api_key": "3f4c6e9a7b12d5e8f0a3c2b1d4e6f8a0b2c4d6e8f0a2c4b6d8e0f2a4b6c8d0e2",
  "docs_url": "https://api.beatpillz.com/api-docs"
}
```

---

### `POST /user/api-key/generate`
* **Auth**: Bearer Token Required
* **Description**: Generates or regenerates a new developer API key for the authenticated user (same sha256 generation logic as web).
* **Response `200 OK`**:
```json
{
  "success": true,
  "message": "API key generated successfully.",
  "api_key": "a1b2c3d4e5f60718293a4b5c6d7e8f90123456789abcdef0123456789abcdef0",
  "docs_url": "https://api.beatpillz.com/api-docs"
}
```

---

## 5. Home Discovery, Categories & Producers

### `GET /home`
* **Auth**: Public
* **Description**: Bundled home discovery payload optimized for mobile screens.
* **Response `200 OK`**:
```json
{
  "success": true,
  "data": {
    "featured_beats": [ ...ItemResource ],
    "trending_beats": [ ...ItemResource ],
    "best_selling_beats": [ ...ItemResource ],
    "latest_beats": [ ...ItemResource ],
    "categories": [ ...CategoryResource ],
    "featured_producers": [ ...UserResource ]
  }
}
```

---

### `GET /categories`
* **Auth**: Public
* **Description**: Lists all genres / categories with nested subcategories and item counts.
* **Response `200 OK`**:
```json
{
  "success": true,
  "categories": [
    {
      "id": 1,
      "name": "Trap",
      "slug": "trap",
      "description": "Hard-hitting trap and drill instrumentals",
      "icon": "fas fa-drum",
      "items_count": 142,
      "subcategories": [
        {
          "id": 4,
          "name": "Dark Trap",
          "slug": "dark-trap",
          "items_count": 58
        }
      ]
    }
  ]
}
```

---

### `GET /producers/{username_or_id}`
* **Auth**: Public (Optional Bearer Token: if passed, populates `is_following` boolean)
* **Description**: Returns public producer profile details, follow status, and summary counts.
* **Response `200 OK`**:
```json
{
  "success": true,
  "data": {
    "profile": {
      "id": 5,
      "firstname": "Metro",
      "lastname": "Boomin",
      "fullname": "Metro Boomin",
      "username": "metroboomin",
      "email": "metro@example.com",
      "avatar": "https://domain.com/storage/avatars/metro.jpg",
      "profile_cover": "https://domain.com/storage/covers/metro-banner.jpg",
      "profile_heading": "Platinum Multi-Genre Producer",
      "profile_description": "Official BeatPillz store. Dark Trap, Drill, and R&B beats.",
      "is_author": true,
      "is_featured_author": true,
      "exclusivity": "exclusive",
      "address": {
        "city": "Atlanta",
        "state": "GA",
        "country": "US",
        "country_name": "United States"
      },
      "total_sales": 320,
      "total_reviews": 48,
      "avg_reviews": 4.9,
      "total_followers": 1250,
      "total_following": 42,
      "profile_contact_email": "mgmt@metroboomin.com",
      "social_links": {
        "instagram": "https://instagram.com/metroboomin",
        "twitter": "https://x.com/metroboomin",
        "spotify": "https://open.spotify.com/artist/metro",
        "youtube": "https://youtube.com/metroboomin"
      },
      "created_at": "2024-01-15T12:00:00.000000Z"
    },
    "is_following": true,
    "counts": {
      "portfolio": 36,
      "followers": 1250,
      "following": 42,
      "reviews": 48,
      "avg_rating": 4.9,
      "total_sales": 320
    }
  }
}
```

---

### `GET /producers/{username_or_id}/portfolio`
* **Auth**: Public
* **Description**: Paginated beats uploaded by the producer, with search, genre/category filters, and sorting.
* **Query Parameters**:
  | Parameter | Type | Default | Description |
  |---|---|---|---|
  | `page` | `int` | `1` | Page number |
  | `per_page` | `int` | `15` | Beats per page |
  | `search` / `q` | `string` | `null` | Keyword search in beat title, tags, description |
  | `category` | `string|int` | `null` | Filter by category slug or ID |
  | `sort` | `string` | `latest` | `latest`, `popular`, `rating`, `price_low`, `price_high`, `oldest` |
* **Response `200 OK`**:
```json
{
  "success": true,
  "data": [ ...ItemResource ],
  "meta": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 15,
    "total": 36
  }
}
```

---

### `GET /producers/{username_or_id}/reviews`
* **Auth**: Public
* **Description**: Paginated reviews left by listeners for this producer's beats, plus a star rating breakdown (1 to 5 stars).
* **Query Parameters**:
  | Parameter | Type | Default | Description |
  |---|---|---|---|
  | `page` | `int` | `1` | Page number |
  | `per_page` | `int` | `15` | Reviews per page |
* **Response `200 OK`**:
```json
{
  "success": true,
  "stats": {
    "total_reviews": 48,
    "avg_rating": 4.9,
    "rating_breakdown": {
      "5": 44,
      "4": 3,
      "3": 1,
      "2": 0,
      "1": 0
    }
  },
  "data": [ ...ReviewResource ],
  "meta": {
    "current_page": 1,
    "last_page": 4,
    "per_page": 15,
    "total": 48
  }
}
```

---

### `GET /producers/{username_or_id}/followers`
* **Auth**: Public (Optional Bearer Token: checks `is_following` per user)
* **Description**: Paginated list of users following this producer.
* **Query Parameters**:
  | Parameter | Type | Default | Description |
  |---|---|---|---|
  | `page` | `int` | `1` | Page number |
  | `per_page` | `int` | `15` | Followers per page |
* **Response `200 OK`**:
```json
{
  "success": true,
  "total_followers": 1250,
  "data": [
    {
      "id": 88,
      "username": "listener_dave",
      "firstname": "Dave",
      "lastname": "Walker",
      "fullname": "Dave Walker",
      "avatar": "https://domain.com/storage/avatars/dave.jpg",
      "profile_heading": "Artist / Songwriter",
      "is_author": false,
      "total_followers": 5,
      "total_following": 18,
      "is_following": false,
      "followed_at": "2024-02-20T14:15:00.000000Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 84,
    "per_page": 15,
    "total": 1250
  }
}
```

---

### `GET /producers/{username_or_id}/following`
* **Auth**: Public (Optional Bearer Token: checks `is_following` per user)
* **Description**: Paginated list of producers/users followed by this producer.
* **Query Parameters**:
  | Parameter | Type | Default | Description |
  |---|---|---|---|
  | `page` | `int` | `1` | Page number |
  | `per_page` | `int` | `15` | Following per page |
* **Response `200 OK`**:
```json
{
  "success": true,
  "total_following": 42,
  "data": [
    {
      "id": 12,
      "username": "southside",
      "firstname": "Southside",
      "lastname": "808",
      "fullname": "Southside 808",
      "avatar": "https://domain.com/storage/avatars/southside.jpg",
      "profile_heading": "808 Mafia Boss",
      "is_author": true,
      "total_followers": 2300,
      "total_following": 85,
      "is_following": true,
      "followed_at": "2024-01-10T11:00:00.000000Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 15,
    "total": 42
  }
}
```

---

### `POST /producers/{id}/follow`
* **Auth**: Bearer Token Required
* **Description**: Toggles follow / unfollow on a producer.
* **Response `200 OK`**:
```json
{
  "success": true,
  "is_following": true,
  "message": "Following producer."
}
```

---

### `POST /producers/{username_or_id}/contact`
* **Auth**: Bearer Token Required
* **Description**: Send inquiry / booking / collaboration message directly to the producer from a listener.
* **Request Body**:
```json
{
  "subject": "Exclusive beat inquiry",
  "message": "Hi Metro, I'm interested in discussing exclusive rights for your Superhero Trap Beat."
}
```
* **Response `200 OK`**:
```json
{
  "success": true,
  "message": "Your message has been sent to metroboomin."
}
```

---

### `GET /user/following`
* **Auth**: Bearer Token Required
* **Description**: Lists all producers the current user follows.
* **Response `200 OK`**:
```json
{
  "success": true,
  "data": [ ...UserResource ]
}
```

---

## 6. Beats Marketplace & Audio Streaming

### `GET /items`
* **Auth**: Public (passes `is_favorited` if token sent)
* **Query Parameters**:
  | Parameter | Type | Example | Description |
  | :--- | :--- | :--- | :--- |
  | `q` | `string` | `?q=drake` | Keyword search in name, description, tags |
  | `category` | `string|int` | `?category=trap` | Filter by category slug or ID |
  | `subcategory` | `string|int` | `?subcategory=dark-trap` | Filter by subcategory slug or ID |
  | `author` | `string|int` | `?author=metro` | Filter by producer username or ID |
  | `is_free` | `int` | `?is_free=1` | Filter only free beats |
  | `is_premium` | `int` | `?is_premium=1` | Filter subscription-only beats |
  | `min_price` | `float` | `?min_price=15` | Minimum price filter |
  | `max_price` | `float` | `?max_price=80` | Maximum price filter |
  | `sort` | `string` | `?sort=popular` | `latest`, `price_low`, `price_high`, `popular`, `rating`, `oldest` |
  | `per_page` | `int` | `?per_page=20` | Items per page (default: 15, max: 50) |
  | `page` | `int` | `?page=2` | Pagination page number |
* **Response `200 OK`**:
```json
{
  "success": true,
  "data": [
    {
      "id": 12,
      "name": "Midnight Ride (Trap Beat)",
      "slug": "midnight-ride-trap-beat",
      "description": "Hard 808s and melancholic flute melody. 140 BPM, C# Minor.",
      "preview_type": "audio",
      "preview_audio_url": "https://beatpillz.com/storage/previews/audio/track_12.mp3",
      "preview_video_url": null,
      "preview_image_url": "https://beatpillz.com/storage/previews/covers/cover_12.jpg",
      "thumbnail_url": "https://beatpillz.com/storage/thumbnails/thumb_12.jpg",
      "price": {
        "regular": 29.99,
        "extended": 99.99,
        "has_discount": true,
        "discount_regular": 19.99,
        "discount_percent": 33
      },
      "is_free": false,
      "is_premium": false,
      "is_trending": true,
      "is_best_selling": false,
      "is_featured": true,
      "is_favorited": false,
      "total_sales": 48,
      "total_reviews": 12,
      "avg_reviews": 4.9,
      "category": {
        "id": 1,
        "name": "Trap",
        "slug": "trap"
      },
      "subcategory": {
        "id": 4,
        "name": "Dark Trap",
        "slug": "dark-trap"
      },
      "author": {
        "id": 3,
        "name": "MetroBeatz",
        "username": "metrobeatz",
        "avatar": "https://beatpillz.com/storage/images/avatars/metro.jpg",
        "is_author": true
      },
      "tags": ["trap", "flute", "808", "dark"],
      "created_at": "2026-03-01T10:00:00.000000Z",
      "updated_at": "2026-03-02T12:00:00.000000Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 8,
    "per_page": 15,
    "total": 120
  }
}
```

---

### `GET /items/{slug_or_id}`
* **Auth**: Public
* **Description**: Returns single beat details, plus related beats in same genre and more beats from the same producer.
* **Response `200 OK`**:
```json
{
  "success": true,
  "item": { ...ItemResource },
  "related": [ ...ItemResource ],
  "author_more": [ ...ItemResource ]
}
```

---

### `GET /items/{id}/download-free`
* **Auth**: Public
* **Description**: Directly downloads or streams audio for items marked `is_free: true`.
* **Response**: Binary audio/zip download stream. Returns `400` if the item is not free.

---

## 7. Reviews, Comments & Wishlist

### `GET /items/{id}/reviews`
* **Auth**: Public
* **Response `200 OK`**:
```json
{
  "success": true,
  "reviews": [
    {
      "id": 5,
      "rating": 5,
      "subject": "Fire drums and clean mix!",
      "body": "The stems were nicely organized and the melody is catchy.",
      "user": {
        "id": 8,
        "name": "Alex R.",
        "username": "alexr",
        "avatar": "https://beatpillz.com/storage/..."
      },
      "created_at": "2026-02-28T14:22:00.000000Z"
    }
  ]
}
```

---

### `POST /items/{id}/reviews`
* **Auth**: Bearer Token Required
* **Requirement**: The caller **must have purchased** the beat (`403 Forbidden` if not).
* **Payload**:
```json
{
  "rating": 5,
  "subject": "Great production quality",
  "body": "Vocals sit perfectly on this mix."
}
```
* **Validation**: `rating: 1-5`, `subject: max 150`, `body: max 2000`
* **Response `200 OK`**:
```json
{
  "success": true,
  "message": "Review submitted successfully.",
  "review": { ... }
}
```

---

### `GET /items/{id}/comments`
* **Auth**: Public
* **Description**: Threaded public Q&A comments on the beat with author replies.
* **Response `200 OK`**:
```json
{
  "success": true,
  "comments": [
    {
      "id": 14,
      "body": "Does the extended license include trackout WAV stems?",
      "user": { "id": 9, "name": "Dave", "username": "dave", "avatar": null },
      "replies": [
        {
          "id": 15,
          "body": "Yes Dave! Extended includes full dry and wet 24-bit WAV stems.",
          "user": { "id": 3, "name": "MetroBeatz", "username": "metrobeatz", "avatar": "..." },
          "created_at": "2026-03-01T15:00:00.000000Z"
        }
      ],
      "created_at": "2026-03-01T14:30:00.000000Z"
    }
  ]
}
```

---

### `POST /items/{id}/comments`
* **Auth**: Bearer Token Required
* **Payload**:
```json
{
  "body": "Are the samples royalty-free for Spotify distribution?"
}
```
* **Validation**: `body: required, string, max:1000`
* **Response `201 Created`**:
```json
{
  "success": true,
  "message": "Comment posted successfully.",
  "comment": { ... }
}
```

---

### `POST /items/{id}/favorite`
* **Auth**: Bearer Token Required
* **Description**: Toggles beat in/out of user's wishlist.
* **Response `200 OK`**:
```json
{
  "success": true,
  "is_favorited": true,
  "message": "Added to wishlist." // or "Removed from wishlist."
}
```

---

### `GET /favorites`
* **Auth**: Bearer Token Required
* **Response `200 OK`**:
```json
{
  "success": true,
  "data": [ ...ItemResource ]
}
```

---

## 8. Shopping Cart & Multi-License

### `GET /cart`
* **Auth**: Bearer Token Required
* **Description**: Retrieves items currently in user's cart, license breakdown, and calculated subtotal.
* **Response `200 OK`**:
```json
{
  "success": true,
  "data": {
    "items": [
      {
        "id": 21,
        "license_type": 1,
        "license_name": "Regular License",
        "quantity": 1,
        "total_amount": 29.99,
        "item": { ...ItemResource },
        "created_at": "2026-03-02T10:00:00.000000Z"
      }
    ],
    "items_count": 1,
    "subtotal": 29.99,
    "currency": "USD"
  }
}
```

---

### `POST /cart/add`
* **Auth**: Bearer Token Required
* **Payload**:
```json
{
  "item_id": 12,
  "license_type": 1 // 1: Regular License, 2: Extended License
}
```
* **Validation**:
  * `item_id`: required, exists:items,id
  * `license_type`: required, in:1,2
* **Restrictions**: Producers cannot add their own beats (`400 Bad Request`).
* **Response `200 OK`**:
```json
{
  "success": true,
  "message": "Item added to cart."
}
```

---

### `DELETE /cart/{id}`
* **Auth**: Bearer Token Required
* **Description**: Deletes a specific cart row by ID.
* **Response `200 OK`**:
```json
{
  "success": true,
  "message": "Item removed from cart."
}
```

---

### `DELETE /cart/clear`
* **Auth**: Bearer Token Required
* **Description**: Empties entire cart.
* **Response `200 OK`**:
```json
{
  "success": true,
  "message": "Cart cleared."
}
```

---

## 9. Mobile Checkout & 1-Click Wallet Payments

### `GET /checkout/gateways`
* **Auth**: Bearer Token Required
* **Description**: Returns user's wallet balance and active payment gateways (Stripe, PayPal, Razorpay, etc.).
* **Response `200 OK`**:
```json
{
  "success": true,
  "user_balance": 50.00,
  "gateways": [
    {
      "id": 1,
      "name": "Paystack",
      "alias": "paystack",
      "logo": "https://beatpillz.com/storage/gateways/paystack.png",
      "fees": 3.0,
      "is_sandbox": false
    },
    {
      "id": 2,
      "name": "Flutterwave",
      "alias": "flutterwave",
      "logo": "https://beatpillz.com/storage/gateways/flutterwave.png",
      "fees": 2.5,
      "is_sandbox": false
    }
  ]
}
```

---

### `GET /checkout/subscription/{plan_id}`
* **Auth**: Bearer Token Required
* **Description**: Returns all data required for the Native Subscription Checkout Screen (Plan details, user billing address, and gateways with pre-calculated fees and totals).
* **Path Parameter**: `plan_id` (integer Plan ID)
* **Response `200 OK`**:
```json
{
  "success": true,
  "plan": {
    "id": 1,
    "name": "Basic Creators",
    "interval": "month",
    "interval_name": "Monthly",
    "price": 5.99,
    "is_free": false
  },
  "user_balance": 25.50,
  "billing_address": {
    "firstname": "Ali",
    "lastname": "Raxa",
    "address_line_1": "streeet 1",
    "address_line_2": "street 2",
    "city": "Lahore",
    "state": "Punjab",
    "zip": "64000",
    "country": "Pakistan"
  },
  "gateways": [
    {
      "id": 1,
      "name": "Paystack",
      "alias": "paystack",
      "logo": "https://beatpillz.com/storage/gateways/paystack.png",
      "fee_percentage": 3.0,
      "fee_amount": 0.18,
      "subtotal": 5.99,
      "total": 6.17
    },
    {
      "id": 2,
      "name": "Flutterwave",
      "alias": "flutterwave",
      "logo": "https://beatpillz.com/storage/gateways/flutterwave.png",
      "fee_percentage": 2.5,
      "fee_amount": 0.15,
      "subtotal": 5.99,
      "total": 6.14
    }
  ]
}
```

---

### `POST /checkout/subscription`
* **Auth**: Bearer Token Required
* **Description**: Submit subscription checkout with billing address and selected payment method (instant wallet payment or gateway session).
* **Payload**:
```json
{
  "plan_id": 1,
  "payment_method": "paystack",
  "billing_address": {
    "firstname": "Ali",
    "lastname": "Raxa",
    "address_line_1": "streeet 1",
    "address_line_2": "street 2",
    "city": "Lahore",
    "state": "Punjab",
    "zip": "64000",
    "country": "Pakistan"
  }
}
```
* **Response `200 OK` (When Paying with Wallet Balance `payment_method: "balance"`)**:
```json
{
  "success": true,
  "is_paid": true,
  "message": "Subscribed successfully using your wallet balance.",
  "user_balance": 19.51,
  "subscription": {
    "id": 15,
    "plan_id": 1,
    "plan_name": "Basic Creators",
    "expires_at": "2026-10-18T16:00:00.000000Z"
  },
  "order_summary": {
    "item_title": "Subscription - Basic Creators (Monthly)",
    "subtotal": 5.99,
    "fee_percentage": 0.0,
    "fee_amount": 0.0,
    "total": 5.99
  }
}
```
* **Response `200 OK` (When Paying with Gateway e.g. `paystack` / `flutterwave`)**:
```json
{
  "success": true,
  "is_paid": false,
  "transaction_id": 52,
  "payment_method": "paystack",
  "checkout_url": "https://beatpillz.com/checkout/a1b2c3d4",
  "order_summary": {
    "item_title": "Subscription - Basic Creators (Monthly)",
    "subtotal": 5.99,
    "fee_percentage": 3.0,
    "fee_amount": 0.18,
    "total": 6.17
  }
}
```

---

### `POST /checkout/create-transaction`
* **Auth**: Bearer Token Required
* **Description**: Converts the user's active cart into an unpaid transaction record.
* **Response `201 Created`**:
```json
{
  "success": true,
  "transaction_id": 42,
  "total_amount": 29.99,
  "currency": "USD",
  "user_balance": 50.00,
  "can_pay_balance": true
}
```

---

### `POST /checkout/pay-with-balance`
* **Auth**: Bearer Token Required
* **Description**: 1-Click payment using account wallet balance. Automatically:
  1. Deducts balance from buyer.
  2. Marks transaction as paid.
  3. Creates `Purchase` records with unique download license codes.
  4. Credits author balance (70% commission).
  5. Clears the buyer's cart.
* **Payload**:
```json
{
  "transaction_id": 42
}
```
* **Response `200 OK`**:
```json
{
  "success": true,
  "message": "Purchase completed successfully!",
  "new_balance": 20.01,
  "transaction_id": 42
}
```

---

## 10. User Library, Downloads & Statements

### `GET /user/purchases`
* **Auth**: Bearer Token Required
* **Query**: `q` (optional search keyword for purchase code or beat title), `page`
* **Response `200 OK`**:
```json
{
  "success": true,
  "data": [
    {
      "id": 18,
      "purchase_code": "BP-ABCD-1234-EFGH-5678",
      "license_type": 1,
      "license_name": "Regular License",
      "is_downloaded": false,
      "status": 1,
      "support_expiry_at": "2026-09-01T12:00:00.000000Z",
      "is_support_expired": false,
      "item": { ...ItemResource },
      "has_reviewed": false,
      "review": null,
      "created_at": "2026-03-02T11:00:00.000000Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 15,
    "total": 1
  }
}
```

---

### `GET /user/purchases/{id}/download`
* **Auth**: Bearer Token Required
* **Description**: Streams the production beat file package (Lossless audio, MP3, Stems & License Certificate).
* **Response**: Binary file download stream.

---

### `GET /user/purchases/{id}/license`
* **Auth**: Bearer Token Required
* **Description**: Returns formatted legal license certificate data for rendering an in-app certificate agreement view or printable PDF.
* **Path Parameter**: `id` (integer purchase ID)
* **Response `200 OK`**:
```json
{
  "success": true,
  "certificate": {
    "title": "License Certificate",
    "purchase_code": "8A9B2C4D-5E6F-7G8H",
    "license_type": 1,
    "license_name": "Regular License",
    "item": {
      "id": 12,
      "name": "Midnight Ride (Trap Beat)",
      "slug": "midnight-ride-trap-beat",
      "url": "https://beatpillz.com/beats/midnight-ride-trap-beat/12",
      "thumbnail": "https://beatpillz.com/images/beats/thumb.jpg"
    },
    "licensor": {
      "id": 4,
      "name": "MetroBeats",
      "username": "metrobeats"
    },
    "licensee": {
      "id": 8,
      "name": "John Doe",
      "username": "johndoe",
      "email": "johndoe@example.com"
    },
    "purchase_date": "2026-03-02T11:00:00.000000Z",
    "support_expiry_at": "2026-09-02T11:00:00.000000Z",
    "is_support_expired": false,
    "web_certificate_url": "https://beatpillz.com/workspace/purchases/14/license",
    "legal_notice": "This document certifies the legal purchase of the specified license. The licensee is granted permission to use the work in accordance with the license tier terms."
  }
}
```

---

### `POST /tools/verify-license`
* **Auth**: Bearer Token Required (Author / Producer)
* **Description**: Producer license verification tool to verify whether a client/artist holds a genuine license for an item from their catalog.
* **Body Payload**:
```json
{
  "purchase_code": "8A9B2C4D-5E6F-7G8H"
}
```
* **Response `200 OK` (Valid License)**:
```json
{
  "success": true,
  "is_valid": true,
  "verification": {
    "purchase_code": "8A9B2C4D-5E6F-7G8H",
    "license_type": 1,
    "license_name": "Regular License",
    "item": {
      "id": 12,
      "name": "Midnight Ride (Trap Beat)",
      "slug": "midnight-ride-trap-beat",
      "url": "https://beatpillz.com/beats/midnight-ride-trap-beat/12"
    },
    "buyer": {
      "id": 8,
      "name": "John Doe",
      "username": "johndoe",
      "avatar": "https://beatpillz.com/images/avatars/user.jpg"
    },
    "purchase_date": "2026-03-02T11:00:00.000000Z",
    "support_expiry_at": "2026-09-02T11:00:00.000000Z",
    "is_support_expired": false,
    "is_downloaded": true
  }
}
```
* **Response `404 Not Found` (Invalid / Fraudulent Code)**:
```json
{
  "success": false,
  "is_valid": false,
  "message": "Invalid purchase code or no matching item found in your catalog."
}
```

---

### `GET /user/statements`
* **Auth**: Bearer Token Required
* **Description**: Financial transaction history and wallet credit/debit statements.
* **Response `200 OK`**:
```json
{
  "success": true,
  "data": [
    {
      "id": 104,
      "title": "Purchased Midnight Ride (Trap Beat)",
      "amount": -29.99,
      "total": 20.01,
      "type": 2,
      "item": {
        "id": 12,
        "name": "Midnight Ride (Trap Beat)",
        "slug": "midnight-ride-trap-beat"
      },
      "created_at": "2026-03-02T11:00:00.000000Z"
    }
  ],
  "meta": { ... }
}
```

---

## 11. Author / Producer Studio

> **Note**: All endpoints in this section require `is_author: true` (`403 Forbidden` otherwise).

### `GET /author/dashboard`
* **Auth**: Bearer Token Required
* **Response `200 OK`**:
```json
{
  "success": true,
  "data": {
    "balance": 340.50,
    "total_sales": 18,
    "total_sales_amount": 485.00,
    "total_reviews": 14,
    "avg_reviews": 4.9,
    "total_followers": 230,
    "pending_withdrawals": 0.00,
    "items_count": 8,
    "approved_items_count": 7,
    "pending_items_count": 1
  }
}
```

---

### `GET /author/items`
* **Auth**: Bearer Token Required
* **Query**: `status` (optional: `1: Pending`, `2: Soft Rejected`, `4: Approved`), `page`
* **Response `200 OK`**:
```json
{
  "success": true,
  "data": [
    {
      "id": 12,
      "name": "Midnight Ride (Trap Beat)",
      "slug": "midnight-ride-trap-beat",
      "status": 4,
      "status_name": "Approved",
      "regular_price": 29.99,
      "total_sales": 18,
      "thumbnail_url": "https://beatpillz.com/storage/thumbnails/thumb_12.jpg",
      "created_at": "2026-02-10T08:00:00.000000Z"
    }
  ],
  "meta": { ... }
}
```

---

### `POST /author/items/upload`
* **Auth**: Bearer Token Required
* **Content-Type**: `multipart/form-data`
* **Form Fields**:
  | Field | Type | Required | Notes |
  | :--- | :--- | :--- | :--- |
  | `name` | `string` | Yes | Max: 150 chars |
  | `category_id` | `int` | Yes | Category ID |
  | `sub_category_id` | `int` | No | SubCategory ID |
  | `description` | `string` | Yes | Detailed track description |
  | `regular_price` | `numeric` | Yes | Min: $1 |
  | `extended_price` | `numeric` | No | Default: `regular_price * 2` |
  | `tags` | `string` | No | Comma-separated |
  | `preview_audio` | `file` | No | Audio (`mp3, wav, ogg, m4a`, max: 30MB) |
  | `thumbnail` | `file` | No | Cover image (`jpeg, png, jpg, webp`, max: 5MB) |
* **Response `201 Created`**:
```json
{
  "success": true,
  "message": "Beat uploaded successfully and submitted for review.",
  "item": {
    "id": 19,
    "name": "New Horizons (Afrobeats)",
    "slug": "new-horizons-afrobeats",
    "status": 1,
    "status_name": "Pending",
    "regular_price": 34.99
  }
}
```

---

### `DELETE /author/items/{id}`
* **Auth**: Bearer Token Required
* **Restriction**: Items that already have sales cannot be deleted (`400 Bad Request`).
* **Response `200 OK`**:
```json
{
  "success": true,
  "message": "Beat deleted successfully."
}
```

---

### `GET /author/sales`
* **Auth**: Bearer Token Required
* **Response `200 OK`**:
```json
{
  "success": true,
  "data": [
    {
      "id": 31,
      "price": 29.99,
      "author_earning": 20.99,
      "license_type": 1,
      "item": { "id": 12, "name": "Midnight Ride", "slug": "midnight-ride" },
      "buyer": { "name": "Alex R.", "username": "alexr" },
      "created_at": "2026-03-01T18:00:00.000000Z"
    }
  ],
  "meta": { ... }
}
```

---

### `GET /author/withdrawals`
* **Auth**: Bearer Token Required
* **Description**: Returns list of available payout gateways, minimum withdrawal limits, and payout history.
* **Response `200 OK`**:
```json
{
  "success": true,
  "data": {
    "available_methods": [
      { "id": 1, "name": "PayPal", "minimum": 50.00 },
      { "id": 2, "name": "Bank Transfer", "minimum": 100.00 }
    ],
    "current_method": {
      "id": 1,
      "name": "PayPal",
      "account": "payouts@mybeats.com"
    },
    "history": [
      {
        "id": 4,
        "amount": 250.00,
        "method": "PayPal",
        "account": "payouts@mybeats.com",
        "status": 1, // 1: Pending, 2: Paid
        "created_at": "2026-02-15T09:00:00.000000Z"
      }
    ]
  }
}
```

---

### `POST /author/withdrawals/request`
* **Auth**: Bearer Token Required
* **Description**: Requests payout of all available author balance to the configured account.
* **Response `200 OK`**:
```json
{
  "success": true,
  "message": "Withdrawal request submitted successfully."
}
```

---

## 12. Subscriptions & Premium Plans

### `GET /plans`
* **Auth**: Public
* **Response `200 OK`**:
```json
{
  "success": true,
  "plans": [
    {
      "id": 1,
      "name": "Producer Pro",
      "short_description": "Unlimited downloads and zero marketplace commission fees",
      "interval": "month",
      "interval_name": "Monthly",
      "price": 19.99,
      "is_free": false,
      "is_featured": true,
      "custom_features": ["Unlimited MP3 Downloads", "Direct Stems Access", "VIP Support"],
      "downloads": 50
    }
  ]
}
```

---

### `GET /user/subscription`
* **Auth**: Bearer Token Required
* **Response `200 OK`**:
```json
{
  "success": true,
  "is_subscribed": true,
  "subscription": {
    "id": 3,
    "plan_id": 1,
    "plan_name": "Producer Pro",
    "interval_name": "Monthly",
    "price": 19.99,
    "status": 1,
    "total_downloads": 12,
    "downloads_limit": 50,
    "is_unlimited": false,
    "is_daily_limit_reached": false,
    "is_expired": false,
    "is_about_to_expire": false,
    "days_remaining": 22,
    "expires_at": "2026-10-18T16:00:00.000000Z",
    "created_at": "2026-09-18T16:00:00.000000Z"
  }
}
```

---

### `POST /plans/{id}/subscribe`
Subscribe to a free plan directly, pay for a plan using account balance, or initialize a gateway transaction.
* **Auth**: Bearer Token Required
* **Path Parameter**: `id` (integer plan ID)
* **Optional Body Parameters**:
  * `pay_with_balance` (boolean, optional): Set to `true` to pay immediately using available wallet balance.

* **Response for Free Plan / Wallet Balance (`200 OK`)**:
```json
{
  "success": true,
  "message": "Subscribed successfully using your wallet balance.",
  "is_subscribed": true,
  "user_balance": 80.01,
  "subscription": {
    "id": 4,
    "plan_id": 1,
    "plan_name": "Producer Pro",
    "expires_at": "2026-10-18T16:00:00.000000Z"
  }
}
```

* **Response for Gateway Checkout Initialization (`200 OK`)**:
```json
{
  "success": true,
  "message": "Subscription transaction initialized.",
  "transaction_id": 42,
  "transaction_hash": "a1b2c3d4",
  "amount": 19.99,
  "checkout_url": "https://beatpillz.com/checkout/a1b2c3d4",
  "user_balance": 5.00,
  "can_pay_with_balance": false
}
```

---

### `GET /items/{id}/download-premium`
Directly download a premium beat file using an active subscriber membership without adding to cart or purchasing separately.
* **Auth**: Bearer Token Required (Active Subscription Required)
* **Path Parameter**: `id` (integer item ID)
* **Response `200 OK`**: File download binary stream or JSON download object for external storage:
```json
{
  "success": true,
  "is_external": true,
  "download_url": "https://storage.beatpillz.com/beats/stems-archive.zip",
  "filename": "stems-archive.zip"
}
```
* **Error States**:
  * `403 Forbidden`: Unsubscribed user or expired subscription.
  * `429 Too Many Requests`: User reached daily/monthly download quota.

---

---

## 13. Refund Requests

### `GET /refunds`
* **Auth**: Bearer Token Required
* **Response `200 OK`**: Lists refund requests where the user is either the buyer or the author.

---

### `POST /refunds`
* **Auth**: Bearer Token Required
* **Payload**:
```json
{
  "purchase_id": 18,
  "reason": "Corrupted audio stems",
  "message": "Track 4 in the stems archive has audio glitches."
}
```
* **Validation**: `purchase_id: required, exists:purchases,id`, `reason: max:255`, `message: max:3000`
* **Response `201 Created`**:
```json
{
  "success": true,
  "message": "Refund request submitted to producer.",
  "refund": {
    "id": 6,
    "status": 1,
    "created_at": "2026-03-03T10:00:00.000000Z"
  }
}
```

---

### `GET /refunds/{id}`
* **Auth**: Bearer Token Required
* **Description**: Returns refund conversation thread and messages.
* **Response `200 OK`**:
```json
{
  "success": true,
  "data": {
    "id": 6,
    "reason": "Corrupted audio stems",
    "status": 1,
    "item": { "id": 12, "name": "Midnight Ride" },
    "replies": [
      {
        "id": 11,
        "body": "Hi, I just re-exported the track 4 WAV and updated the download archive.",
        "sender": { "name": "MetroBeatz", "is_admin": false },
        "created_at": "2026-03-03T11:00:00.000000Z"
      }
    ],
    "created_at": "2026-03-03T10:00:00.000000Z"
  }
}
```

---

### `POST /refunds/{id}/reply`
* **Auth**: Bearer Token Required
* **Payload**:
```json
{
  "message": "Thank you, the new download works perfectly!"
}
```
* **Response `201 Created`**:
```json
{
  "success": true,
  "message": "Reply posted."
}
```

---

## 14. Support Tickets & FAQs

### `GET /tickets`
* **Auth**: Bearer Token Required
* **Response `200 OK`**: Lists all user support tickets with status names.

---

### `GET /tickets/categories`
* **Auth**: Bearer Token Required
* **Response `200 OK`**: Returns ticket categories (`Account`, `Billing`, `Technical`, etc.).

---

### `POST /tickets`
* **Auth**: Bearer Token Required
* **Payload**:
```json
{
  "ticket_category_id": 2,
  "subject": "Payout delay inquiry",
  "message": "My PayPal payout was approved yesterday but has not arrived."
}
```
* **Response `201 Created`**:
```json
{
  "success": true,
  "message": "Support ticket created successfully.",
  "ticket": {
    "id": 15,
    "subject": "Payout delay inquiry",
    "status": 1,
    "created_at": "2026-03-03T12:00:00.000000Z"
  }
}
```

---

### `GET /tickets/{id}`
* **Auth**: Bearer Token Required
* **Response `200 OK`**: Returns ticket thread and admin replies.

---

### `POST /tickets/{id}/reply`
* **Auth**: Bearer Token Required
* **Payload**:
```json
{
  "message": "Received the funds now, you can close this ticket. Thanks!"
}
```
* **Response `201 Created`**:
```json
{
  "success": true,
  "message": "Reply submitted."
}
```

---

### `GET /help/categories`
* **Auth**: Public
* **Response `200 OK`**: Help categories and articles.

---

### `GET /help/article/{slug}`
* **Auth**: Public
* **Response `200 OK`**: Full help article body.

---

### `GET /help/faqs`
* **Auth**: Public
* **Response `200 OK`**: Lists platform FAQs with titles and answers.

---

## 15. Blog & News

### `GET /blog`
* **Auth**: Public
* **Query**: `category` (slug or id), `page`
* **Response `200 OK`**: Published articles list.

---

### `GET /blog/{slug}`
* **Auth**: Public
* **Response `200 OK`**: Article body, category, view count.

---

## 16. Flutter / Dart HTTP Service Boilerplate

Save this directly into your mobile app project under `lib/core/services/api_service.dart`:

```dart
import 'dart:convert';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:http/http.dart' as http;

class BeatPillzApiService {
  static const String baseUrl = 'https://beatpillz.com/api/v1';
  static const FlutterSecureStorage _storage = FlutterSecureStorage();

  static Future<Map<String, String>> _headers({bool requiresAuth = true}) async {
    final headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    };
    if (requiresAuth) {
      final token = await _storage.read(key: 'auth_token');
      if (token != null) {
        headers['Authorization'] = 'Bearer $token';
      }
    }
    return headers;
  }

  // 1. Login with Email or Username
  static Future<Map<String, dynamic>> login(String login, String password) async {
    final response = await http.post(
      Uri.parse('$baseUrl/auth/login'),
      headers: await _headers(requiresAuth: false),
      body: jsonEncode({
        'login': login,
        'password': password,
        'device_name': 'Mobile Client',
      }),
    );
    final data = jsonDecode(response.body);
    if (response.statusCode == 200 && data['success'] == true) {
      await _storage.write(key: 'auth_token', value: data['access_token']);
    }
    return data;
  }

  // 2. Native Social Login
  static Future<Map<String, dynamic>> socialLogin({
    required String provider,
    required String providerId,
    required String email,
    String? firstname,
    String? lastname,
    String? avatar,
  }) async {
    final response = await http.post(
      Uri.parse('$baseUrl/auth/social-login'),
      headers: await _headers(requiresAuth: false),
      body: jsonEncode({
        'provider': provider,
        'provider_id': providerId,
        'email': email,
        'firstname': firstname,
        'lastname': lastname,
        'avatar': avatar,
        'device_name': 'Mobile Client',
      }),
    );
    final data = jsonDecode(response.body);
    if (response.statusCode == 200 && data['success'] == true) {
      await _storage.write(key: 'auth_token', value: data['access_token']);
    }
    return data;
  }

  // 3. Home Discovery Feed
  static Future<Map<String, dynamic>> getHomeFeed() async {
    final response = await http.get(
      Uri.parse('$baseUrl/home'),
      headers: await _headers(requiresAuth: false),
    );
    return jsonDecode(response.body);
  }

  // 4. Catalog Search & Filter
  static Future<Map<String, dynamic>> getBeats({
    String? query,
    String? category,
    String sort = 'latest',
    int page = 1,
  }) async {
    final uri = Uri.parse('$baseUrl/items').replace(queryParameters: {
      if (query != null && query.isNotEmpty) 'q': query,
      if (category != null && category.isNotEmpty) 'category': category,
      'sort': sort,
      'page': '$page',
    });
    final response = await http.get(uri, headers: await _headers());
    return jsonDecode(response.body);
  }

  // 5. 1-Click Wallet Checkout
  static Future<Map<String, dynamic>> payWithBalance(int transactionId) async {
    final response = await http.post(
      Uri.parse('$baseUrl/checkout/pay-with-balance'),
      headers: await _headers(),
      body: jsonEncode({'transaction_id': transactionId}),
    );
    return jsonDecode(response.body);
  }

  // 6. User Purchased Library
  static Future<Map<String, dynamic>> getPurchases({int page = 1}) async {
    final uri = Uri.parse('$baseUrl/user/purchases').replace(queryParameters: {'page': '$page'});
    final response = await http.get(uri, headers: await _headers());
    return jsonDecode(response.body);
  }

  // 7. Logout
  static Future<void> logout() async {
    try {
      await http.post(Uri.parse('$baseUrl/auth/logout'), headers: await _headers());
    } catch (_) {}
    await _storage.delete(key: 'auth_token');
  }
}
```
