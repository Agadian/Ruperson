import React, { useState } from 'react';
import { View, Text, TextInput, TouchableOpacity, StyleSheet, ScrollView, KeyboardAvoidingView, Platform, Alert } from 'react-native';
import { useAuth } from '../contexts/AuthContext';
import { useTheme } from '../contexts/ThemeContext';

export default function RegisterScreen({ navigation }) {
  const { register } = useAuth();
  const { theme } = useTheme();
  const [fullName, setFullName] = useState('');
  const [email, setEmail] = useState('');
  const [phone, setPhone] = useState('');
  const [password, setPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [loading, setLoading] = useState(false);

  const handleRegister = async () => {
    if (!fullName || !email || !phone || !password) {
      Alert.alert('Error', 'Please fill in all fields');
      return;
    }
    if (password !== confirmPassword) {
      Alert.alert('Error', 'Passwords do not match');
      return;
    }
    if (password.length < 8) {
      Alert.alert('Error', 'Password must be at least 8 characters');
      return;
    }

    setLoading(true);
    try {
      await register({ full_name: fullName, email, phone, password });
    } catch (error) {
      Alert.alert('Registration Failed', error.response?.data?.message || 'Please try again');
    } finally {
      setLoading(false);
    }
  };

  const s = styles(theme);

  return (
    <KeyboardAvoidingView style={s.container} behavior={Platform.OS === 'ios' ? 'padding' : 'height'}>
      <ScrollView contentContainerStyle={s.scroll} keyboardShouldPersistTaps="handled">
        <View style={s.header}>
          <Text style={s.logo}>Veh<Text style={s.logoAccent}>doc</Text></Text>
          <Text style={s.title}>Create Account</Text>
          <Text style={s.subtitle}>Start processing your vehicle documents today</Text>
        </View>

        <View style={s.form}>
          <View style={s.inputGroup}>
            <Text style={s.label}>Full Name</Text>
            <TextInput style={s.input} placeholder="Enter your full name" placeholderTextColor={theme.textMuted} value={fullName} onChangeText={setFullName} />
          </View>
          <View style={s.inputGroup}>
            <Text style={s.label}>Email Address</Text>
            <TextInput style={s.input} placeholder="you@example.com" placeholderTextColor={theme.textMuted} value={email} onChangeText={setEmail} keyboardType="email-address" autoCapitalize="none" />
          </View>
          <View style={s.inputGroup}>
            <Text style={s.label}>Phone Number</Text>
            <TextInput style={s.input} placeholder="+234..." placeholderTextColor={theme.textMuted} value={phone} onChangeText={setPhone} keyboardType="phone-pad" />
          </View>
          <View style={s.inputGroup}>
            <Text style={s.label}>Password</Text>
            <TextInput style={s.input} placeholder="Min. 8 characters" placeholderTextColor={theme.textMuted} value={password} onChangeText={setPassword} secureTextEntry />
          </View>
          <View style={s.inputGroup}>
            <Text style={s.label}>Confirm Password</Text>
            <TextInput style={s.input} placeholder="Confirm your password" placeholderTextColor={theme.textMuted} value={confirmPassword} onChangeText={setConfirmPassword} secureTextEntry />
          </View>

          <TouchableOpacity style={[s.btn, loading && s.btnDisabled]} onPress={handleRegister} disabled={loading}>
            <Text style={s.btnText}>{loading ? 'Creating Account...' : 'Create Account'}</Text>
          </TouchableOpacity>
        </View>

        <View style={s.footer}>
          <Text style={s.footerText}>
            Already have an account?{' '}
            <Text style={s.link} onPress={() => navigation.navigate('Login')}>Log in</Text>
          </Text>
        </View>
      </ScrollView>
    </KeyboardAvoidingView>
  );
}

const styles = (theme) => StyleSheet.create({
  container: { flex: 1, backgroundColor: theme.bg },
  scroll: { flexGrow: 1, justifyContent: 'center', padding: 24 },
  header: { alignItems: 'center', marginBottom: 32 },
  logo: { fontSize: 32, fontWeight: '800', color: theme.primary },
  logoAccent: { color: theme.accent },
  title: { fontSize: 24, fontWeight: '800', color: theme.text, marginTop: 16 },
  subtitle: { fontSize: 14, color: theme.textSecondary, marginTop: 8 },
  form: { marginBottom: 24 },
  inputGroup: { marginBottom: 16 },
  label: { fontSize: 13, fontWeight: '600', color: theme.textSecondary, marginBottom: 6 },
  input: {
    backgroundColor: theme.bgSecondary, borderWidth: 1, borderColor: theme.border,
    borderRadius: 10, padding: 14, fontSize: 15, color: theme.text,
  },
  btn: { backgroundColor: theme.accent, borderRadius: 12, padding: 16, alignItems: 'center', marginTop: 8 },
  btnDisabled: { opacity: 0.6 },
  btnText: { color: '#fff', fontSize: 16, fontWeight: '700' },
  footer: { alignItems: 'center' },
  footerText: { fontSize: 14, color: theme.textSecondary },
  link: { color: theme.accent, fontWeight: '600' },
});
