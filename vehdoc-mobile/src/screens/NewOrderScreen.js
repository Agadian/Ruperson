import React, { useState, useEffect } from 'react';
import { View, Text, ScrollView, TouchableOpacity, StyleSheet, Alert } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { useTheme } from '../contexts/ThemeContext';
import { getServices, getVehicles, createOrder } from '../utils/api';

export default function NewOrderScreen({ route, navigation }) {
  const { theme } = useTheme();
  const preSelectedService = route.params?.serviceId;
  const preSelectedVehicle = route.params?.vehicleId;

  const [services, setServices] = useState([]);
  const [vehicles, setVehicles] = useState([]);
  const [selectedService, setSelectedService] = useState(preSelectedService || null);
  const [selectedVehicle, setSelectedVehicle] = useState(preSelectedVehicle || null);
  const [fastTrack, setFastTrack] = useState(false);
  const [delivery, setDelivery] = useState(true);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    Promise.all([getServices(), getVehicles()]).then(([sRes, vRes]) => {
      setServices(sRes.data || []);
      setVehicles(vRes.data || []);
    }).catch(() => {});
  }, []);

  const selectedServiceData = services.find(s => s.id === selectedService);
  const basePrice = selectedServiceData ? Number(selectedServiceData.price) : 0;
  const total = (fastTrack && selectedServiceData?.fast_track_price ? Number(selectedServiceData.fast_track_price) : basePrice) + (delivery ? 3000 : 0);

  const handleSubmit = async () => {
    if (!selectedService) { Alert.alert('Error', 'Please select a service'); return; }
    if (!selectedVehicle) { Alert.alert('Error', 'Please select a vehicle'); return; }

    setLoading(true);
    try {
      const res = await createOrder({
        service_id: selectedService,
        vehicle_id: selectedVehicle,
        fast_track: fastTrack,
        delivery: delivery,
      });
      Alert.alert('Order Created!', 'Your order has been placed successfully.', [
        { text: 'Track Order', onPress: () => navigation.replace('OrderTracking', { orderId: res.data.order_id }) }
      ]);
    } catch (error) {
      Alert.alert('Error', error.response?.data?.message || 'Failed to create order');
    } finally {
      setLoading(false);
    }
  };

  const s = styles(theme);

  return (
    <ScrollView style={s.container}>
      {/* Step 1: Select Service */}
      <View style={s.step}>
        <Text style={s.stepTitle}><Text style={s.stepNum}>1 </Text>Select Service</Text>
        {services.map((svc) => (
          <TouchableOpacity key={svc.id} style={[s.optionCard, selectedService === svc.id && s.optionCardActive]}
            onPress={() => setSelectedService(svc.id)}>
            <View style={{ flex: 1 }}>
              <Text style={s.optionTitle}>{svc.title}</Text>
              <Text style={s.optionSub}>{svc.processing_time}</Text>
            </View>
            <Text style={s.optionPrice}>₦{Number(svc.price).toLocaleString()}</Text>
          </TouchableOpacity>
        ))}
      </View>

      {/* Step 2: Select Vehicle */}
      <View style={s.step}>
        <Text style={s.stepTitle}><Text style={s.stepNum}>2 </Text>Select Vehicle</Text>
        {vehicles.length === 0 ? (
          <TouchableOpacity style={s.addBtn} onPress={() => navigation.navigate('AddVehicle')}>
            <Ionicons name="add" size={20} color={theme.accent} />
            <Text style={{ color: theme.accent, fontWeight: '600' }}>Add Vehicle First</Text>
          </TouchableOpacity>
        ) : vehicles.map((v) => (
          <TouchableOpacity key={v.id} style={[s.optionCard, selectedVehicle === v.id && s.optionCardActive]}
            onPress={() => setSelectedVehicle(v.id)}>
            <Ionicons name="car" size={22} color={theme.accent} style={{ marginRight: 12 }} />
            <View style={{ flex: 1 }}>
              <Text style={s.optionTitle}>{v.make} {v.model}</Text>
              <Text style={s.optionSub}>{v.plate_number}</Text>
            </View>
          </TouchableOpacity>
        ))}
      </View>

      {/* Step 3: Options */}
      <View style={s.step}>
        <Text style={s.stepTitle}><Text style={s.stepNum}>3 </Text>Options</Text>
        <TouchableOpacity style={[s.toggleCard, fastTrack && s.toggleActive]} onPress={() => setFastTrack(!fastTrack)}>
          <Ionicons name="flash" size={22} color={fastTrack ? theme.orange : theme.textMuted} />
          <View style={{ flex: 1, marginLeft: 12 }}>
            <Text style={s.toggleTitle}>Fast-Track Processing</Text>
            <Text style={s.toggleSub}>Get processed faster</Text>
          </View>
          <Ionicons name={fastTrack ? 'checkbox' : 'square-outline'} size={24} color={fastTrack ? theme.accent : theme.textMuted} />
        </TouchableOpacity>
        <TouchableOpacity style={[s.toggleCard, delivery && s.toggleActive]} onPress={() => setDelivery(!delivery)}>
          <Ionicons name="bicycle" size={22} color={delivery ? theme.green : theme.textMuted} />
          <View style={{ flex: 1, marginLeft: 12 }}>
            <Text style={s.toggleTitle}>Doorstep Delivery</Text>
            <Text style={s.toggleSub}>₦3,000 delivery fee</Text>
          </View>
          <Ionicons name={delivery ? 'checkbox' : 'square-outline'} size={24} color={delivery ? theme.accent : theme.textMuted} />
        </TouchableOpacity>
      </View>

      {/* Summary */}
      <View style={s.summary}>
        <Text style={s.summaryTitle}>Order Summary</Text>
        <View style={s.summaryRow}>
          <Text style={s.summaryLabel}>Service</Text>
          <Text style={s.summaryValue}>{selectedServiceData?.title || '—'}</Text>
        </View>
        <View style={s.summaryRow}>
          <Text style={s.summaryLabel}>Service Fee</Text>
          <Text style={s.summaryValue}>₦{basePrice.toLocaleString()}</Text>
        </View>
        <View style={s.summaryRow}>
          <Text style={s.summaryLabel}>Delivery</Text>
          <Text style={s.summaryValue}>₦{delivery ? '3,000' : '0'}</Text>
        </View>
        <View style={[s.summaryRow, s.summaryTotal]}>
          <Text style={s.totalLabel}>Total</Text>
          <Text style={s.totalValue}>₦{total.toLocaleString()}</Text>
        </View>
      </View>

      <TouchableOpacity style={[s.submitBtn, loading && { opacity: 0.6 }]} onPress={handleSubmit} disabled={loading}>
        <Text style={s.submitText}>{loading ? 'Creating Order...' : 'Proceed to Payment'}</Text>
      </TouchableOpacity>

      <View style={{ height: 40 }} />
    </ScrollView>
  );
}

const styles = (theme) => StyleSheet.create({
  container: { flex: 1, backgroundColor: theme.bg, padding: 20 },
  step: { marginBottom: 28 },
  stepTitle: { fontSize: 16, fontWeight: '700', color: theme.text, marginBottom: 14 },
  stepNum: { color: theme.accent, fontSize: 18, fontWeight: '800' },
  optionCard: {
    flexDirection: 'row', alignItems: 'center', backgroundColor: theme.surface,
    borderRadius: 12, padding: 16, marginBottom: 10,
    borderWidth: 2, borderColor: theme.border,
  },
  optionCardActive: { borderColor: theme.accent, backgroundColor: `${theme.accent}08` },
  optionTitle: { fontSize: 14, fontWeight: '600', color: theme.text },
  optionSub: { fontSize: 12, color: theme.textMuted, marginTop: 2 },
  optionPrice: { fontSize: 16, fontWeight: '800', color: theme.accent },
  addBtn: {
    flexDirection: 'row', alignItems: 'center', justifyContent: 'center', gap: 8,
    padding: 16, borderWidth: 2, borderColor: theme.accent, borderStyle: 'dashed',
    borderRadius: 12,
  },
  toggleCard: {
    flexDirection: 'row', alignItems: 'center', backgroundColor: theme.surface,
    borderRadius: 12, padding: 16, marginBottom: 10,
    borderWidth: 1, borderColor: theme.border,
  },
  toggleActive: { borderColor: theme.accent, backgroundColor: `${theme.accent}08` },
  toggleTitle: { fontSize: 14, fontWeight: '600', color: theme.text },
  toggleSub: { fontSize: 12, color: theme.textMuted },
  summary: {
    backgroundColor: theme.bgSecondary, borderRadius: 16, padding: 20,
    marginBottom: 20, borderWidth: 1, borderColor: theme.border,
  },
  summaryTitle: { fontSize: 16, fontWeight: '700', color: theme.text, marginBottom: 14 },
  summaryRow: { flexDirection: 'row', justifyContent: 'space-between', paddingVertical: 8 },
  summaryLabel: { fontSize: 14, color: theme.textSecondary },
  summaryValue: { fontSize: 14, fontWeight: '600', color: theme.text },
  summaryTotal: { borderTopWidth: 2, borderTopColor: theme.border, marginTop: 8, paddingTop: 12 },
  totalLabel: { fontSize: 16, fontWeight: '700', color: theme.text },
  totalValue: { fontSize: 20, fontWeight: '800', color: theme.accent },
  submitBtn: { backgroundColor: theme.accent, borderRadius: 14, padding: 18, alignItems: 'center' },
  submitText: { color: '#fff', fontSize: 17, fontWeight: '700' },
});
