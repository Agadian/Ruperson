import React, { useState } from 'react';
import { View, Text, TextInput, TouchableOpacity, StyleSheet, ScrollView, Alert } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { useAuth } from '../contexts/AuthContext';
import { useTheme } from '../contexts/ThemeContext';
import { updateProfile } from '../utils/api';

export default function ProfileScreen({ navigation }) {
  const { user, logout } = useAuth();
  const { theme, isDark, toggleTheme } = useTheme();
  const [firstName, setFirstName] = useState(user?.first_name || '');
  const [lastName, setLastName] = useState(user?.last_name || '');
  const [phone, setPhone] = useState(user?.phone || '');
  const [loading, setLoading] = useState(false);

  const handleSave = async () => {
    setLoading(true);
    try {
      await updateProfile({ first_name: firstName, last_name: lastName, phone });
      Alert.alert('Success', 'Profile updated');
    } catch {
      Alert.alert('Error', 'Failed to update profile');
    } finally {
      setLoading(false);
    }
  };

  const handleLogout = () => {
    Alert.alert('Logout', 'Are you sure you want to log out?', [
      { text: 'Cancel', style: 'cancel' },
      { text: 'Logout', style: 'destructive', onPress: logout },
    ]);
  };

  const s = styles(theme);

  return (
    <ScrollView style={s.container}>
      <View style={s.avatarSection}>
        <View style={s.avatar}>
          <Ionicons name="person" size={40} color={theme.textMuted} />
        </View>
        <Text style={s.name}>{user?.display_name}</Text>
        <Text style={s.email}>{user?.email}</Text>
      </View>

      <View style={s.section}>
        <Text style={s.sectionTitle}>Personal Information</Text>
        <View style={s.row}>
          <View style={s.inputGroup}>
            <Text style={s.label}>First Name</Text>
            <TextInput style={s.input} value={firstName} onChangeText={setFirstName} placeholderTextColor={theme.textMuted} />
          </View>
          <View style={s.inputGroup}>
            <Text style={s.label}>Last Name</Text>
            <TextInput style={s.input} value={lastName} onChangeText={setLastName} placeholderTextColor={theme.textMuted} />
          </View>
        </View>
        <View style={s.inputGroup}>
          <Text style={s.label}>Phone Number</Text>
          <TextInput style={s.input} value={phone} onChangeText={setPhone} keyboardType="phone-pad" placeholderTextColor={theme.textMuted} />
        </View>
        <TouchableOpacity style={[s.saveBtn, loading && { opacity: 0.6 }]} onPress={handleSave} disabled={loading}>
          <Text style={s.saveBtnText}>{loading ? 'Saving...' : 'Update Profile'}</Text>
        </TouchableOpacity>
      </View>

      <View style={s.section}>
        <Text style={s.sectionTitle}>Quick Links</Text>
        {[
          { icon: 'car', label: 'My Vehicles', screen: 'Vehicles' },
          { icon: 'notifications', label: 'Notifications', screen: 'Notifications' },
        ].map((item, i) => (
          <TouchableOpacity key={i} style={s.menuItem} onPress={() => navigation.navigate(item.screen)}>
            <View style={s.menuIcon}><Ionicons name={item.icon} size={20} color={theme.accent} /></View>
            <Text style={s.menuLabel}>{item.label}</Text>
            <Ionicons name="chevron-forward" size={18} color={theme.textMuted} />
          </TouchableOpacity>
        ))}
        <TouchableOpacity style={s.menuItem} onPress={toggleTheme}>
          <View style={s.menuIcon}><Ionicons name={isDark ? 'sunny' : 'moon'} size={20} color={theme.orange} /></View>
          <Text style={s.menuLabel}>{isDark ? 'Light Mode' : 'Dark Mode'}</Text>
          <Ionicons name="chevron-forward" size={18} color={theme.textMuted} />
        </TouchableOpacity>
      </View>

      <TouchableOpacity style={s.logoutBtn} onPress={handleLogout}>
        <Ionicons name="log-out-outline" size={20} color={theme.red} />
        <Text style={s.logoutText}>Logout</Text>
      </TouchableOpacity>

      <View style={{ height: 40 }} />
    </ScrollView>
  );
}

const styles = (theme) => StyleSheet.create({
  container: { flex: 1, backgroundColor: theme.bg, padding: 20 },
  avatarSection: { alignItems: 'center', marginBottom: 28, paddingTop: 10 },
  avatar: {
    width: 80, height: 80, borderRadius: 40, backgroundColor: theme.bgTertiary,
    justifyContent: 'center', alignItems: 'center', marginBottom: 12,
  },
  name: { fontSize: 20, fontWeight: '800', color: theme.text },
  email: { fontSize: 14, color: theme.textMuted, marginTop: 4 },
  section: { marginBottom: 28 },
  sectionTitle: { fontSize: 16, fontWeight: '700', color: theme.text, marginBottom: 14 },
  row: { flexDirection: 'row', gap: 12 },
  inputGroup: { flex: 1, marginBottom: 16 },
  label: { fontSize: 13, fontWeight: '600', color: theme.textSecondary, marginBottom: 6 },
  input: {
    backgroundColor: theme.bgSecondary, borderWidth: 1, borderColor: theme.border,
    borderRadius: 10, padding: 14, fontSize: 15, color: theme.text,
  },
  saveBtn: { backgroundColor: theme.accent, borderRadius: 12, padding: 14, alignItems: 'center' },
  saveBtnText: { color: '#fff', fontSize: 15, fontWeight: '700' },
  menuItem: {
    flexDirection: 'row', alignItems: 'center', backgroundColor: theme.surface,
    borderRadius: 12, padding: 16, marginBottom: 8,
    borderWidth: 1, borderColor: theme.border,
  },
  menuIcon: {
    width: 36, height: 36, borderRadius: 10, backgroundColor: `${theme.accent}15`,
    justifyContent: 'center', alignItems: 'center', marginRight: 14,
  },
  menuLabel: { flex: 1, fontSize: 15, fontWeight: '500', color: theme.text },
  logoutBtn: {
    flexDirection: 'row', alignItems: 'center', justifyContent: 'center', gap: 8,
    padding: 16, borderRadius: 12, borderWidth: 1, borderColor: theme.red,
    backgroundColor: `${theme.red}08`,
  },
  logoutText: { fontSize: 15, fontWeight: '600', color: theme.red },
});
