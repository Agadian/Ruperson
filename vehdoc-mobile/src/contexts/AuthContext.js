import React, { createContext, useState, useEffect, useContext } from 'react';
import * as SecureStore from 'expo-secure-store';
import { login as apiLogin, register as apiRegister, getProfile } from '../utils/api';

const AuthContext = createContext(null);

export function AuthProvider({ children }) {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);
  const [token, setToken] = useState(null);

  useEffect(() => {
    checkAuth();
  }, []);

  const checkAuth = async () => {
    try {
      const savedToken = await SecureStore.getItemAsync('authToken');
      if (savedToken) {
        setToken(savedToken);
        const response = await getProfile();
        setUser(response.data);
      }
    } catch {
      await SecureStore.deleteItemAsync('authToken');
    } finally {
      setLoading(false);
    }
  };

  const login = async (email, password) => {
    const response = await apiLogin(email, password);
    const { token: newToken, user: userData } = response.data;
    await SecureStore.setItemAsync('authToken', newToken);
    setToken(newToken);
    setUser(userData);
    return response.data;
  };

  const register = async (data) => {
    const response = await apiRegister(data);
    const { token: newToken, user: userData } = response.data;
    await SecureStore.setItemAsync('authToken', newToken);
    setToken(newToken);
    setUser(userData);
    return response.data;
  };

  const logout = async () => {
    await SecureStore.deleteItemAsync('authToken');
    setToken(null);
    setUser(null);
  };

  return (
    <AuthContext.Provider value={{ user, token, loading, login, register, logout, checkAuth }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  const context = useContext(AuthContext);
  if (!context) throw new Error('useAuth must be used within AuthProvider');
  return context;
}

export default AuthContext;
