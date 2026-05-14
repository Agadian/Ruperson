import React, { useState, useEffect } from 'react';
import { View, Text, FlatList, TouchableOpacity, StyleSheet, RefreshControl } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { useTheme } from '../contexts/ThemeContext';
import { getOrders } from '../utils/api';

const STATUS_COLORS = {
  pending: '#f59e0b', documents_received: '#3b82f6', processing: '#8b5cf6',
  approved: '#10b981', ready_for_delivery: '#06b6d4', delivered: '#22c55e', cancelled: '#ef4444',
};

export default function OrdersScreen({ navigation }) {
  const { theme } = useTheme();
  const [orders, setOrders] = useState([]);
  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => { fetchData(); }, []);

  const fetchData = async () => {
    try {
      const res = await getOrders();
      setOrders(res.data || []);
    } catch {}
  };

  const s = styles(theme);

  const renderOrder = ({ item }) => (
    <TouchableOpacity style={s.card} onPress={() => navigation.navigate('OrderTracking', { orderId: item.id })}>
      <View style={s.header}>
        <Text style={s.orderNum}>VHD-{String(item.id).padStart(6, '0')}</Text>
        <View style={[s.badge, { backgroundColor: (STATUS_COLORS[item.status] || theme.textMuted) + '20' }]}>
          <Text style={[s.badgeText, { color: STATUS_COLORS[item.status] || theme.textMuted }]}>
            {item.status?.replace(/_/g, ' ')}
          </Text>
        </View>
      </View>
      <Text style={s.service}>{item.service_name}</Text>
      <View style={s.footer}>
        <Text style={s.amount}>₦{Number(item.amount || 0).toLocaleString()}</Text>
        <Text style={s.date}>{item.date}</Text>
      </View>
      {item.status === 'pending' && (
        <TouchableOpacity style={s.uploadBtn} onPress={() => navigation.navigate('DocumentUpload', { orderId: item.id })}>
          <Ionicons name="cloud-upload" size={16} color={theme.accent} />
          <Text style={s.uploadText}>Upload Documents</Text>
        </TouchableOpacity>
      )}
    </TouchableOpacity>
  );

  return (
    <View style={s.container}>
      <TouchableOpacity style={s.newBtn} onPress={() => navigation.navigate('NewOrder')}>
        <Ionicons name="add" size={20} color="#fff" />
        <Text style={s.newBtnText}>New Order</Text>
      </TouchableOpacity>
      <FlatList
        data={orders}
        renderItem={renderOrder}
        keyExtractor={(item) => String(item.id)}
        contentContainerStyle={s.list}
        ListEmptyComponent={
          <View style={s.empty}>
            <Ionicons name="clipboard-outline" size={48} color={theme.textMuted} />
            <Text style={s.emptyText}>No orders yet</Text>
          </View>
        }
        refreshControl={<RefreshControl refreshing={refreshing} onRefresh={async () => { setRefreshing(true); await fetchData(); setRefreshing(false); }} tintColor={theme.accent} />}
      />
    </View>
  );
}

const styles = (theme) => StyleSheet.create({
  container: { flex: 1, backgroundColor: theme.bg },
  newBtn: {
    flexDirection: 'row', alignItems: 'center', justifyContent: 'center', gap: 8,
    backgroundColor: theme.accent, marginHorizontal: 20, marginTop: 16,
    borderRadius: 12, padding: 14,
  },
  newBtnText: { color: '#fff', fontSize: 15, fontWeight: '700' },
  list: { padding: 20 },
  card: {
    backgroundColor: theme.surface, borderRadius: 14, padding: 16,
    marginBottom: 12, borderWidth: 1, borderColor: theme.border,
  },
  header: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginBottom: 8 },
  orderNum: { fontSize: 14, fontWeight: '700', color: theme.text },
  badge: { paddingHorizontal: 10, paddingVertical: 4, borderRadius: 20 },
  badgeText: { fontSize: 11, fontWeight: '600', textTransform: 'capitalize' },
  service: { fontSize: 14, color: theme.textSecondary, marginBottom: 10 },
  footer: { flexDirection: 'row', justifyContent: 'space-between' },
  amount: { fontSize: 16, fontWeight: '700', color: theme.accent },
  date: { fontSize: 12, color: theme.textMuted, alignSelf: 'center' },
  uploadBtn: {
    flexDirection: 'row', alignItems: 'center', justifyContent: 'center', gap: 6,
    marginTop: 12, paddingVertical: 10, borderRadius: 8,
    borderWidth: 1, borderColor: theme.accent, backgroundColor: `${theme.accent}08`,
  },
  uploadText: { fontSize: 13, color: theme.accent, fontWeight: '600' },
  empty: { alignItems: 'center', paddingVertical: 60 },
  emptyText: { fontSize: 16, color: theme.textMuted, marginTop: 12 },
});
