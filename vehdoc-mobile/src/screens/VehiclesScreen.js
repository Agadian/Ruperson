import React, { useState, useEffect } from 'react';
import { View, Text, FlatList, TouchableOpacity, StyleSheet, RefreshControl, Alert } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { useTheme } from '../contexts/ThemeContext';
import { getVehicles, deleteVehicle } from '../utils/api';

export default function VehiclesScreen({ navigation }) {
  const { theme } = useTheme();
  const [vehicles, setVehicles] = useState([]);
  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => { fetchData(); }, []);

  const fetchData = async () => {
    try {
      const res = await getVehicles();
      setVehicles(res.data || []);
    } catch {}
  };

  const handleDelete = (id) => {
    Alert.alert('Delete Vehicle', 'Are you sure you want to remove this vehicle?', [
      { text: 'Cancel', style: 'cancel' },
      {
        text: 'Delete', style: 'destructive',
        onPress: async () => {
          try { await deleteVehicle(id); fetchData(); } catch { Alert.alert('Error', 'Failed to delete vehicle'); }
        }
      },
    ]);
  };

  const s = styles(theme);

  const renderVehicle = ({ item }) => (
    <View style={s.card}>
      <View style={s.cardHeader}>
        <View style={s.iconWrap}><Ionicons name="car" size={24} color={theme.accent} /></View>
        <View style={{ flex: 1 }}>
          <Text style={s.title}>{item.make} {item.model}</Text>
          <Text style={s.subtitle}>{item.year} • {item.plate_number}</Text>
        </View>
      </View>
      {item.chassis_number ? <Text style={s.detail}>Chassis: {item.chassis_number}</Text> : null}
      <View style={s.actions}>
        <TouchableOpacity style={s.actionBtn} onPress={() => navigation.navigate('NewOrder', { vehicleId: item.id })}>
          <Ionicons name="add-circle-outline" size={16} color={theme.accent} />
          <Text style={[s.actionText, { color: theme.accent }]}>Order Service</Text>
        </TouchableOpacity>
        <TouchableOpacity style={s.actionBtn} onPress={() => handleDelete(item.id)}>
          <Ionicons name="trash-outline" size={16} color={theme.red} />
          <Text style={[s.actionText, { color: theme.red }]}>Remove</Text>
        </TouchableOpacity>
      </View>
    </View>
  );

  return (
    <View style={s.container}>
      <TouchableOpacity style={s.addBtn} onPress={() => navigation.navigate('AddVehicle')}>
        <Ionicons name="add" size={20} color="#fff" />
        <Text style={s.addBtnText}>Add Vehicle</Text>
      </TouchableOpacity>
      <FlatList
        data={vehicles}
        renderItem={renderVehicle}
        keyExtractor={(item) => String(item.id)}
        contentContainerStyle={s.list}
        ListEmptyComponent={
          <View style={s.empty}>
            <Ionicons name="car-outline" size={48} color={theme.textMuted} />
            <Text style={s.emptyText}>No vehicles added yet</Text>
          </View>
        }
        refreshControl={<RefreshControl refreshing={refreshing} onRefresh={async () => { setRefreshing(true); await fetchData(); setRefreshing(false); }} tintColor={theme.accent} />}
      />
    </View>
  );
}

const styles = (theme) => StyleSheet.create({
  container: { flex: 1, backgroundColor: theme.bg },
  addBtn: {
    flexDirection: 'row', alignItems: 'center', justifyContent: 'center', gap: 8,
    backgroundColor: theme.accent, marginHorizontal: 20, marginTop: 16,
    borderRadius: 12, padding: 14,
  },
  addBtnText: { color: '#fff', fontSize: 15, fontWeight: '700' },
  list: { padding: 20 },
  card: {
    backgroundColor: theme.surface, borderRadius: 14, padding: 16,
    marginBottom: 12, borderWidth: 1, borderColor: theme.border,
  },
  cardHeader: { flexDirection: 'row', alignItems: 'center', marginBottom: 10 },
  iconWrap: {
    width: 48, height: 48, borderRadius: 12, backgroundColor: `${theme.accent}15`,
    justifyContent: 'center', alignItems: 'center', marginRight: 14,
  },
  title: { fontSize: 16, fontWeight: '700', color: theme.text },
  subtitle: { fontSize: 13, color: theme.textMuted, marginTop: 2 },
  detail: { fontSize: 12, color: theme.textMuted, marginBottom: 10 },
  actions: { flexDirection: 'row', gap: 12, marginTop: 8, borderTopWidth: 1, borderTopColor: theme.border, paddingTop: 12 },
  actionBtn: { flexDirection: 'row', alignItems: 'center', gap: 6 },
  actionText: { fontSize: 13, fontWeight: '600' },
  empty: { alignItems: 'center', paddingVertical: 60 },
  emptyText: { fontSize: 16, color: theme.textMuted, marginTop: 12 },
});
