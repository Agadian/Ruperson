import React, { useState, useEffect } from 'react';
import { View, Text, ScrollView, StyleSheet, ActivityIndicator } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { useTheme } from '../contexts/ThemeContext';
import { getOrder } from '../utils/api';

const STEPS = [
  { key: 'pending', label: 'Order Placed', icon: 'receipt', desc: 'Your order has been received' },
  { key: 'documents_received', label: 'Documents Received', icon: 'document-attach', desc: 'We have received your documents' },
  { key: 'processing', label: 'Processing', icon: 'sync', desc: 'Your documents are being processed' },
  { key: 'approved', label: 'Approved', icon: 'checkmark-circle', desc: 'Your documents have been approved' },
  { key: 'ready_for_delivery', label: 'Ready for Delivery', icon: 'gift', desc: 'Documents are ready to be shipped' },
  { key: 'delivered', label: 'Delivered', icon: 'bicycle', desc: 'Documents delivered to your doorstep' },
];

export default function OrderTrackingScreen({ route }) {
  const { orderId } = route.params;
  const { theme } = useTheme();
  const [order, setOrder] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => { fetchOrder(); }, []);

  const fetchOrder = async () => {
    try {
      const res = await getOrder(orderId);
      setOrder(res.data);
    } catch {} finally {
      setLoading(false);
    }
  };

  const s = styles(theme);

  if (loading) {
    return <View style={s.center}><ActivityIndicator size="large" color={theme.accent} /></View>;
  }

  if (!order) {
    return <View style={s.center}><Text style={s.errorText}>Order not found</Text></View>;
  }

  const currentIndex = STEPS.findIndex(step => step.key === order.status);

  return (
    <ScrollView style={s.container}>
      <View style={s.orderInfo}>
        <Text style={s.orderNum}>VHD-{String(order.id).padStart(6, '0')}</Text>
        <Text style={s.orderService}>{order.service_name}</Text>
        <Text style={s.orderAmount}>₦{Number(order.amount || 0).toLocaleString()}</Text>
      </View>

      <View style={s.tracker}>
        {STEPS.map((step, index) => {
          const completed = index <= currentIndex;
          const isCurrent = index === currentIndex;

          return (
            <View key={step.key} style={s.stepRow}>
              <View style={s.stepIndicator}>
                <View style={[
                  s.circle,
                  completed && s.circleCompleted,
                  isCurrent && s.circleCurrent,
                ]}>
                  <Ionicons name={step.icon} size={18} color={completed ? '#fff' : theme.textMuted} />
                </View>
                {index < STEPS.length - 1 && (
                  <View style={[s.line, completed && s.lineCompleted]} />
                )}
              </View>
              <View style={s.stepContent}>
                <Text style={[s.stepLabel, completed && s.stepLabelActive]}>{step.label}</Text>
                <Text style={s.stepDesc}>{step.desc}</Text>
              </View>
            </View>
          );
        })}
      </View>

      <View style={{ height: 40 }} />
    </ScrollView>
  );
}

const styles = (theme) => StyleSheet.create({
  container: { flex: 1, backgroundColor: theme.bg, padding: 20 },
  center: { flex: 1, justifyContent: 'center', alignItems: 'center', backgroundColor: theme.bg },
  errorText: { fontSize: 16, color: theme.textMuted },
  orderInfo: {
    backgroundColor: theme.surface, borderRadius: 16, padding: 20,
    marginBottom: 24, borderWidth: 1, borderColor: theme.border, alignItems: 'center',
  },
  orderNum: { fontSize: 14, fontWeight: '700', color: theme.textMuted, marginBottom: 4 },
  orderService: { fontSize: 18, fontWeight: '700', color: theme.text, marginBottom: 6 },
  orderAmount: { fontSize: 22, fontWeight: '800', color: theme.accent },
  tracker: { paddingLeft: 4 },
  stepRow: { flexDirection: 'row', minHeight: 80 },
  stepIndicator: { alignItems: 'center', marginRight: 16, width: 44 },
  circle: {
    width: 44, height: 44, borderRadius: 22, borderWidth: 2,
    borderColor: theme.border, backgroundColor: theme.surface,
    justifyContent: 'center', alignItems: 'center', zIndex: 1,
  },
  circleCompleted: { backgroundColor: theme.green, borderColor: theme.green },
  circleCurrent: { backgroundColor: theme.accent, borderColor: theme.accent },
  line: { width: 2, flex: 1, backgroundColor: theme.border, marginVertical: 4 },
  lineCompleted: { backgroundColor: theme.green },
  stepContent: { flex: 1, paddingTop: 10, paddingBottom: 20 },
  stepLabel: { fontSize: 15, fontWeight: '700', color: theme.textMuted, marginBottom: 4 },
  stepLabelActive: { color: theme.text },
  stepDesc: { fontSize: 13, color: theme.textMuted },
});
