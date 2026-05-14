import React, { useState, useEffect } from 'react';
import { View, Text, FlatList, TouchableOpacity, StyleSheet, RefreshControl } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { useTheme } from '../contexts/ThemeContext';
import { getNotifications, markNotificationRead } from '../utils/api';

export default function NotificationsScreen() {
  const { theme } = useTheme();
  const [notifications, setNotifications] = useState([]);
  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => { fetchData(); }, []);

  const fetchData = async () => {
    try {
      const res = await getNotifications();
      setNotifications(res.data || []);
    } catch {}
  };

  const handleRead = async (id) => {
    try {
      await markNotificationRead(id);
      setNotifications(prev => prev.map(n => n.id === id ? { ...n, read: true } : n));
    } catch {}
  };

  const s = styles(theme);

  const renderItem = ({ item }) => (
    <TouchableOpacity style={[s.card, !item.read && s.unread]} onPress={() => handleRead(item.id)}>
      <View style={[s.icon, { backgroundColor: item.type === 'expiry_reminder' ? `${theme.orange}15` : `${theme.accent}15` }]}>
        <Ionicons name={item.type === 'expiry_reminder' ? 'alarm' : 'notifications'} size={20}
          color={item.type === 'expiry_reminder' ? theme.orange : theme.accent} />
      </View>
      <View style={{ flex: 1 }}>
        <Text style={s.message}>{item.message}</Text>
        <Text style={s.time}>{item.timestamp}</Text>
      </View>
    </TouchableOpacity>
  );

  return (
    <View style={s.container}>
      <FlatList
        data={notifications}
        renderItem={renderItem}
        keyExtractor={(item, index) => String(item.id || index)}
        contentContainerStyle={s.list}
        ListEmptyComponent={
          <View style={s.empty}>
            <Ionicons name="notifications-off-outline" size={48} color={theme.textMuted} />
            <Text style={s.emptyText}>No notifications</Text>
          </View>
        }
        refreshControl={<RefreshControl refreshing={refreshing} onRefresh={async () => { setRefreshing(true); await fetchData(); setRefreshing(false); }} tintColor={theme.accent} />}
      />
    </View>
  );
}

const styles = (theme) => StyleSheet.create({
  container: { flex: 1, backgroundColor: theme.bg },
  list: { padding: 20 },
  card: {
    flexDirection: 'row', alignItems: 'center', backgroundColor: theme.surface,
    borderRadius: 12, padding: 16, marginBottom: 8,
    borderWidth: 1, borderColor: theme.border,
  },
  unread: { borderLeftWidth: 3, borderLeftColor: theme.accent },
  icon: {
    width: 40, height: 40, borderRadius: 20,
    justifyContent: 'center', alignItems: 'center', marginRight: 14,
  },
  message: { fontSize: 14, color: theme.text, lineHeight: 20 },
  time: { fontSize: 11, color: theme.textMuted, marginTop: 4 },
  empty: { alignItems: 'center', paddingVertical: 60 },
  emptyText: { fontSize: 16, color: theme.textMuted, marginTop: 12 },
});
