import React, { useState } from 'react';
import { View, Text, TextInput, TouchableOpacity, StyleSheet, ScrollView, Alert } from 'react-native';
import { useTheme } from '../contexts/ThemeContext';
import { createVehicle } from '../utils/api';

export default function AddVehicleScreen({ navigation }) {
  const { theme } = useTheme();
  const [form, setForm] = useState({
    make: '', model: '', year: '', plate_number: '',
    chassis_number: '', engine_number: '', color: '',
  });
  const [loading, setLoading] = useState(false);

  const handleSubmit = async () => {
    if (!form.make || !form.model || !form.plate_number) {
      Alert.alert('Error', 'Please fill in required fields (Make, Model, Plate Number)');
      return;
    }
    setLoading(true);
    try {
      await createVehicle(form);
      Alert.alert('Success', 'Vehicle added successfully', [
        { text: 'OK', onPress: () => navigation.goBack() }
      ]);
    } catch (error) {
      Alert.alert('Error', error.response?.data?.message || 'Failed to add vehicle');
    } finally {
      setLoading(false);
    }
  };

  const s = styles(theme);

  return (
    <ScrollView style={s.container} keyboardShouldPersistTaps="handled">
      {[
        { key: 'make', label: 'Make *', placeholder: 'e.g. Toyota' },
        { key: 'model', label: 'Model *', placeholder: 'e.g. Camry' },
        { key: 'year', label: 'Year', placeholder: 'e.g. 2022', keyboard: 'numeric' },
        { key: 'plate_number', label: 'Plate Number *', placeholder: 'e.g. LAG-234-XY' },
        { key: 'chassis_number', label: 'Chassis Number', placeholder: 'Enter chassis number' },
        { key: 'engine_number', label: 'Engine Number', placeholder: 'Enter engine number' },
        { key: 'color', label: 'Color', placeholder: 'e.g. Silver' },
      ].map(({ key, label, placeholder, keyboard }) => (
        <View key={key} style={s.inputGroup}>
          <Text style={s.label}>{label}</Text>
          <TextInput
            style={s.input}
            placeholder={placeholder}
            placeholderTextColor={theme.textMuted}
            value={form[key]}
            onChangeText={(text) => setForm({ ...form, [key]: text })}
            keyboardType={keyboard || 'default'}
          />
        </View>
      ))}

      <TouchableOpacity style={[s.btn, loading && s.btnDisabled]} onPress={handleSubmit} disabled={loading}>
        <Text style={s.btnText}>{loading ? 'Adding...' : 'Add Vehicle'}</Text>
      </TouchableOpacity>

      <View style={{ height: 40 }} />
    </ScrollView>
  );
}

const styles = (theme) => StyleSheet.create({
  container: { flex: 1, backgroundColor: theme.bg, padding: 20 },
  inputGroup: { marginBottom: 18 },
  label: { fontSize: 13, fontWeight: '600', color: theme.textSecondary, marginBottom: 6 },
  input: {
    backgroundColor: theme.bgSecondary, borderWidth: 1, borderColor: theme.border,
    borderRadius: 10, padding: 14, fontSize: 15, color: theme.text,
  },
  btn: { backgroundColor: theme.accent, borderRadius: 12, padding: 16, alignItems: 'center', marginTop: 8 },
  btnDisabled: { opacity: 0.6 },
  btnText: { color: '#fff', fontSize: 16, fontWeight: '700' },
});
