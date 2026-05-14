# Vehdoc Mobile App

React Native (Expo) mobile app for the Vehdoc vehicle documentation platform.

## Setup

```bash
cd vehdoc-mobile
npm install
npx expo start
```

## Features
- User authentication (login/register)
- Service browsing with pricing in Nigerian Naira (₦)
- Vehicle management (add/edit/delete)
- Order creation with service & vehicle selection
- Real-time order tracking
- Document upload (images & PDFs)
- Push notifications
- Dark/Light mode
- Profile management

## Configuration

Update the API base URL in `src/utils/api.js` to point to your WordPress site:
```js
const API_BASE_URL = 'https://your-site.com/wp-json/vehdoc/v1';
```

## Tech Stack
- React Native with Expo
- React Navigation
- Axios for API calls
- Expo SecureStore for token storage
- Expo Image/Document Picker
