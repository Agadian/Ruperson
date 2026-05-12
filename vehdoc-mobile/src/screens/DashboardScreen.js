import React, { useState, useEffect } from 'react';
import { View, Text, ScrollView, TouchableOpacity, StyleSheet, RefreshControl } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { useAuth } from '../contexts/AuthContext';
import { useTheme } from '../contexts/ThemeContext';
import { getOrders, getVehicles } from '../utils/api';

const STATUS_COLORS = {
  pending: '#f59e0b',
  documents_received: '#3b82f6',
  processing: '#8b5cf6',
  approved: '#10b981',
  ready_for_delivery: '#06b6d4',
  delivered: '#22c55e',
  cancelled: '#ef4444',
};

export default function DashboardScreen({ navigation }) {
  const { user } = useAuth();
  const { theme } = useTheme();
  const [orders, setOrders] = useState([]);
  const [vehicles, setVehicles] = useState([]);
  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => { fetchData(); }, []);

  const fetchData = async () => {
    try {
      const [ordersRes, vehiclesRes] = await Promise.all([getOrders(), getVehicles()]);
      setOrders(ordersRes.data || []);
      setVehicles(vehiclesRes.data || []);
    } catch {}
  };

  const activeOrders = orders.filter(o => !['delivered', 'cancelled'].includes(o.status));
  const completedOrders = orders.filter(o => o.status === 'delivered');

  const s = styles(theme);

  return (
    <ScrollView style={s.container} refreshControl={<RefreshControl refreshing={refreshing} onRefresh={async () => { setRefreshing(true); await fetchData(); setRefreshing(false); }} tintColor={theme.accent} />}>
      <View style={s.statsGrid}>
        <View style={s.statCard}>
          <View style={[s.statIcon, { backgroundColor: `${theme.accent}15` }]}>
            <Ionicons name="car" size={22} color={theme.accent} />
          </View>
          <Text style={s.statValue}>{vehicles.length}</Text>
          <Text style={s.statLabel}>Vehicles</Text>
        </View>
        <View style={s.statCard}>
          <View style={[s.statIcon, { backgroundColor: `${theme.purple}15` }]}>
            <Ionicons name="clipboard" size={22} color={theme.purple} />
          </View>
          <Text style={s.statValue}>{orders.length}</Text>
          <Text style={s.statLabel}>Total Orders</Text>
        </View>
        <View style={s.statCard}>
          <View style={[s.statIcon, { backgroundColor: `${theme.orange}15` }]}>
            <Ionicons name="time" size={22} color={theme.orange} />
          </View>
          <Text style={s.statValue}>{activeOrders.length}</Text>
          <Text style={s.statLabel}>Active</Text>
        </View>
        <View style={s.statCard}>
          <View style={[s.statIcon, { backgroundColor: `${theme.green}15` }]}>
            <Ionicons name="checkmark-circle" size={22} color={theme.green} />
          </View>
          <Text style={s.statValue}>{completedOrders.length}</Text>
          <Text style={s.statLabel}>Completed</Text>
        </View>
      </View>

      {/* Quick Actions */}
      <View style={s.quickActions}>
        <TouchableOpacity style={s.actionBtn} onPress={() => navigation.navigate('NewOrder')}>
          <Ionicons name="add-circle" size={28} color={theme.accent} />
          <Text style={s.actionText}>New Order</Text>
        </TouchableOpacity>
        <TouchableOpacity style={s.actionBtn} onPress={() => navigation.navigate('AddVehicle')}>
          <Ionicons name="car" size={28} color={theme.green} />
          <Text style={s.actionText}>Add Vehicle</Text>
        </TouchableOpacity>
        <TouchableOpacity style={s.actionBtn} onPress={() => navigation.navigate('Notifications')}>
          <Ionicons name="notifications" size={28} color={theme.orange} />
          <Text style={s.actionText}>Alerts</Text>
        </TouchableOpacity>
      </View>

      {/* Recent Orders */}
      <View style={s.section}>
        <View style={s.sectionHeader}>
          <Text style={s.sectionTitle}>Recent Orders</Text>
          <TouchableOpacity onPress={() => navigation.navigate('Orders')}>
            <Text style={s.seeAll}>View All</Text>
          </TouchableOpacity>
        </View>
        {orders.length === 0 ? (
          <View style={s.empty}>
            <Ionicons name="clipboard-outline" size={48} color={theme.textMuted} />
            <Text style={s.emptyText}>No orders yet</Text>
            <TouchableOpacity style={s.emptyBtn} onPress={() => navigation.navigate('NewOrder')}>
              <Text style={s.emptyBtnText}>Create First Order</Text>
            </TouchableOpacity>
          </View>
        ) : orders.slice(0, 5).map((order) => (
          <TouchableOpacity key={order.id} style={s.orderCard} onPress={() => navigation.navigate('OrderTracking', { orderId: order.id })}>
            <View style={s.orderHeader}>
              <Text style={s.orderNum}>VHD-{String(order.id).padStart(6, '0')}</Text>
              <View style={[s.badge, { backgroundColor: (STATUS_COLORS[order.status] || theme.textMuted) + '20' }]}>
                <Text style={[s.badgeText, { color: STATUS_COLORS[order.status] || theme.textMuted }]}>
                  {order.status?.replace(/_/g, ' ')}
                </Text>
              </View>
            </View>
            <Text style={s.orderService}>{order.service_name}</Text>
            <View style={s.orderFooter}>
              <Text style={s.orderAmount}>₦{Number(order.amount || 0).toLocaleString()}</Text>
              <Text style={s.orderDate}>{order.date}</Text>
            </View>
          </TouchableOpacity>
        ))}
      </View>

      <View style={{ height: 32 }} />
    </ScrollView>
  );
}

const styles = (theme) => StyleSheet.create({
  container: { flex: 1, backgroundColor: theme.bg, padding: 20 },
  statsGrid: { flexDirection: 'row', flexWrap: 'wrap', gap: 12, marginBottom: 20 },
  statCard: {
    width: '47%', backgroundColor: theme.surface, borderRadius: 14, padding: 16,
    borderWidth: 1, borderColor: theme.border,
  },
  statIcon: { width: 40, height: 40, borderRadius: 10, justifyContent: 'center', alignItems: 'center', marginBottom: 10 },
  statValue: { fontSize: 24, fontWeight: '800', color: theme.text },
  statLabel: { fontSize: 12, color: theme.textMuted, marginTop: 2 },
  quickActions: { flexDirection: 'row', gap: 12, marginBottom: 24 },
  actionBtn: {
    flex: 1, backgroundColor: theme.surface, borderRadius: 14, padding: 16,
    alignItems: 'center', borderWidth: 1, borderColor: theme.border,
  },
  actionText: { fontSize: 12, fontWeight: '600', color: theme.text, marginTop: 6 },
  section: { marginBottom: 24 },
  sectionHeader: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginBottom: 14 },
  sectionTitle: { fontSize: 18, fontWeight: '800', color: theme.text },
  seeAll: { fontSize: 14, color: theme.accent, fontWeight: '600' },
  empty: { alignItems: 'center', paddingVertical: 40 },
  emptyText: { fontSize: 16, color: theme.textMuted, marginTop: 12 },
  emptyBtn: { backgroundColor: theme.accent, borderRadius: 10, paddingHorizontal: 24, paddingVertical: 12, marginTop: 16 },
  emptyBtnText: { color: '#fff', fontWeight: '700' },
  orderCard: {
    backgroundColor: theme.surface, borderRadius: 14, padding: 16,
    marginBottom: 12, borderWidth: 1, borderColor: theme.border,
  },
  orderHeader: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginBottom: 8 },
  orderNum: { fontSize: 14, fontWeight: '700', color: theme.text },
  badge: { paddingHorizontal: 10, paddingVertical: 4, borderRadius: 20 },
  badgeText: { fontSize: 11, fontWeight: '600', textTransform: 'capitalize' },
  orderService: { fontSize: 14, color: theme.textSecondary, marginBottom: 8 },
  orderFooter: { flexDirection: 'row', justifyContent: 'space-between' },
  orderAmount: { fontSize: 16, fontWeight: '700', color: theme.accent },
  orderDate: { fontSize: 12, color: theme.textMuted },
});
