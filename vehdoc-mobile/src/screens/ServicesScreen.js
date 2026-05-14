import React, { useState, useEffect } from 'react';
import { View, Text, FlatList, TouchableOpacity, StyleSheet, RefreshControl } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { useTheme } from '../contexts/ThemeContext';
import { getServices } from '../utils/api';

export default function ServicesScreen({ navigation }) {
  const { theme } = useTheme();
  const [services, setServices] = useState([]);
  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => { fetchData(); }, []);

  const fetchData = async () => {
    try {
      const res = await getServices();
      setServices(res.data || []);
    } catch {}
  };

  const s = styles(theme);

  const renderService = ({ item }) => (
    <TouchableOpacity style={s.card} onPress={() => navigation.navigate('ServiceDetail', { service: item })}>
      <View style={s.cardHeader}>
        <View style={s.iconWrap}>
          <Ionicons name="document-text" size={24} color={theme.accent} />
        </View>
        <View style={{ flex: 1 }}>
          <Text style={s.cardTitle}>{item.title}</Text>
          <Text style={s.cardTime}><Ionicons name="time-outline" size={12} /> {item.processing_time}</Text>
        </View>
      </View>
      {item.description ? <Text style={s.cardDesc} numberOfLines={2}>{item.description}</Text> : null}
      <View style={s.cardFooter}>
        <Text style={s.price}>₦{Number(item.price).toLocaleString()}</Text>
        {item.fast_track_price ? (
          <Text style={s.fastTrack}><Ionicons name="flash" size={12} /> Fast-track available</Text>
        ) : null}
      </View>
      <TouchableOpacity style={s.cardBtn} onPress={() => navigation.navigate('NewOrder', { serviceId: item.id })}>
        <Text style={s.cardBtnText}>Get Started</Text>
        <Ionicons name="arrow-forward" size={16} color="#fff" />
      </TouchableOpacity>
    </TouchableOpacity>
  );

  return (
    <View style={s.container}>
      <FlatList
        data={services}
        renderItem={renderService}
        keyExtractor={(item) => String(item.id)}
        contentContainerStyle={s.list}
        refreshControl={<RefreshControl refreshing={refreshing} onRefresh={async () => { setRefreshing(true); await fetchData(); setRefreshing(false); }} tintColor={theme.accent} />}
      />
    </View>
  );
}

const styles = (theme) => StyleSheet.create({
  container: { flex: 1, backgroundColor: theme.bg },
  list: { padding: 20 },
  card: {
    backgroundColor: theme.surface, borderRadius: 16, padding: 20,
    marginBottom: 16, borderWidth: 1, borderColor: theme.border,
  },
  cardHeader: { flexDirection: 'row', alignItems: 'center', marginBottom: 12 },
  iconWrap: {
    width: 48, height: 48, borderRadius: 12, backgroundColor: `${theme.accent}15`,
    justifyContent: 'center', alignItems: 'center', marginRight: 14,
  },
  cardTitle: { fontSize: 16, fontWeight: '700', color: theme.text },
  cardTime: { fontSize: 12, color: theme.textMuted, marginTop: 2 },
  cardDesc: { fontSize: 13, color: theme.textSecondary, lineHeight: 20, marginBottom: 12 },
  cardFooter: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginBottom: 14 },
  price: { fontSize: 20, fontWeight: '800', color: theme.accent },
  fastTrack: { fontSize: 12, color: theme.orange, fontWeight: '600' },
  cardBtn: {
    backgroundColor: theme.accent, borderRadius: 10, padding: 14,
    flexDirection: 'row', justifyContent: 'center', alignItems: 'center', gap: 8,
  },
  cardBtnText: { color: '#fff', fontSize: 15, fontWeight: '700' },
});
