import axios from 'axios';
import * as SecureStore from 'expo-secure-store';

const API_BASE_URL = 'https://your-site.com/wp-json/vehdoc/v1';

const api = axios.create({
  baseURL: API_BASE_URL,
  timeout: 15000,
  headers: { 'Content-Type': 'application/json' },
});

api.interceptors.request.use(async (config) => {
  const token = await SecureStore.getItemAsync('authToken');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      SecureStore.deleteItemAsync('authToken');
    }
    return Promise.reject(error);
  }
);

// Auth
export const login = (email, password) =>
  api.post('/login', { email, password });

export const register = (data) =>
  api.post('/register', data);

// Services
export const getServices = () => api.get('/services');
export const getService = (id) => api.get(`/services/${id}`);

// Profile
export const getProfile = () => api.get('/profile');
export const updateProfile = (data) => api.put('/profile', data);

// Vehicles
export const getVehicles = () => api.get('/vehicles');
export const createVehicle = (data) => api.post('/vehicles', data);
export const updateVehicle = (id, data) => api.put(`/vehicles/${id}`, data);
export const deleteVehicle = (id) => api.delete(`/vehicles/${id}`);

// Orders
export const getOrders = () => api.get('/orders');
export const getOrder = (id) => api.get(`/orders/${id}`);
export const createOrder = (data) => api.post('/orders', data);
export const uploadDocuments = (orderId, formData) =>
  api.post(`/orders/${orderId}/documents`, formData, {
    headers: { 'Content-Type': 'multipart/form-data' },
  });

// Payments
export const verifyPayment = (data) => api.post('/payments/verify', data);

// Notifications
export const getNotifications = () => api.get('/notifications');
export const markNotificationRead = (id) =>
  api.post(`/notifications/${id}/read`);

// Delivery
export const scheduleDelivery = (data) => api.post('/delivery/schedule', data);

export default api;
