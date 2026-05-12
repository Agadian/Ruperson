import React, { useState, useEffect } from 'react';
import { View, Text, ScrollView, TouchableOpacity, StyleSheet, RefreshControl } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { useAuth } from '../contexts/AuthContext';
import { useTheme } from '../contexts/ThemeContext';
import { getServices } from '../utils/api';

export default function HomeScreen({ navigation }) {
  const { user } = useAuth();
  const { theme, isDark, toggleTheme } = useTheme();
  const [services, setServices] = useState([]);
  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => { fetchServices(); }, []);

  const fetchServices = async () => {
    try {
      const res = await getServices();
      setServices(res.data || []);
    } catch {}
  };

  const onRefresh = async () => {
    setRefreshing(true);
    await fetchServices();
    setRefreshing(false);
  };

  const s = styles(theme);

  return (
    <ScrollView style={s.container} refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} tintColor={theme.accent} />}>
      {/* Header */}
      <View style={s.header}>
        <View style={s.headerTop}>
          <View>
            <Text style={s.greeting}>Welcome back,</Text>
            <Text style={s.userName}>{user?.display_name || 'User'}</Text>
          </View>
          <View style={s.headerActions}>
            <TouchableOpacity onPress={toggleTheme} style={s.iconBtn}>
              <Ionicons name={isDark ? 'sunny' : 'moon'} size={22} color={theme.textInverse} />
            </TouchableOpacity>
            <TouchableOpacity onPress={() => navigation.navigate('Notifications')} style={s.iconBtn}>
              <Ionicons name="notifications-outline" size={22} color={theme.textInverse} />
            </TouchableOpacity>
          </View>
        </View>
      </View>

      {/* Quick Stats */}
      <View style={s.statsRow}>
        <TouchableOpacity style={s.statCard} onPress={() => navigation.navigate('Vehicles')}>
          <Ionicons name="car" size={24} color={theme.accent} />
          <Text style={s.statLabel}>Vehicles</Text>
        </TouchableOpacity>
        <TouchableOpacity style={s.statCard} onPress={() => navigation.navigate('Orders')}>
          <Ionicons name="clipboard" size={24} color={theme.purple} />
          <Text style={s.statLabel}>Orders</Text>
        </TouchableOpacity>
        <TouchableOpacity style={s.statCard} onPress={() => navigation.navigate('Services')}>
          <Ionicons name="grid" size={24} color={theme.green} />
          <Text style={s.statLabel}>Services</Text>
        </TouchableOpacity>
        <TouchableOpacity style={s.statCard} onPress={() => navigation.navigate('Profile')}>
          <Ionicons name="person" size={24} color={theme.orange} />
          <Text style={s.statLabel}>Profile</Text>
        </TouchableOpacity>
      </View>

      {/* Services */}
      <View style={s.section}>
        <View style={s.sectionHeader}>
          <Text style={s.sectionTitle}>Popular Services</Text>
          <TouchableOpacity onPress={() => navigation.navigate('Services')}>
            <Text style={s.seeAll}>See All</Text>
          </TouchableOpacity>
        </View>
        {services.slice(0, 4).map((service) => (
          <TouchableOpacity key={service.id} style={s.serviceCard} onPress={() => navigation.navigate('ServiceDetail', { service })}>
            <View style={s.serviceIcon}>
              <Ionicons name="document-text" size={24} color={theme.accent} />
            </View>
            <View style={s.serviceInfo}>
              <Text style={s.serviceName}>{service.title}</Text>
              <Text style={s.serviceTime}>{service.processing_time}</Text>
            </View>
            <Text style={s.servicePrice}>₦{Number(service.price).toLocaleString()}</Text>
          </TouchableOpacity>
        ))}
      </View>

      {/* How It Works */}
      <View style={s.section}>
        <Text style={s.sectionTitle}>How It Works</Text>
        {[
          { icon: 'person-add', title: 'Create Account', desc: 'Sign up with your email and phone' },
          { icon: 'list', title: 'Select Service', desc: 'Choose the service you need' },
          { icon: 'card', title: 'Pay Securely', desc: 'Pay online in Naira' },
          { icon: 'bicycle', title: 'Doorstep Delivery', desc: 'Receive documents at your doorstep' },
        ].map((step, i) => (
          <View key={i} style={s.stepRow}>
            <View style={s.stepNum}><Text style={s.stepNumText}>{i + 1}</Text></View>
            <View style={s.stepIcon}><Ionicons name={step.icon} size={20} color={theme.accent} /></View>
            <View style={{ flex: 1 }}>
              <Text style={s.stepTitle}>{step.title}</Text>
              <Text style={s.stepDesc}>{step.desc}</Text>
            </View>
          </View>
        ))}
      </View>

      <View style={{ height: 32 }} />
    </ScrollView>
  );
}

const styles = (theme) => StyleSheet.create({
  container: { flex: 1, backgroundColor: theme.bg },
  header: {
    backgroundColor: theme.primary, paddingTop: 60, paddingBottom: 32,
    paddingHorizontal: 20, borderBottomLeftRadius: 24, borderBottomRightRadius: 24,
  },
  headerTop: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center' },
  greeting: { fontSize: 14, color: 'rgba(255,255,255,0.7)' },
  userName: { fontSize: 22, fontWeight: '800', color: '#fff' },
  headerActions: { flexDirection: 'row', gap: 12 },
  iconBtn: { width: 40, height: 40, borderRadius: 12, backgroundColor: 'rgba(255,255,255,0.15)', justifyContent: 'center', alignItems: 'center' },
  statsRow: { flexDirection: 'row', marginHorizontal: 20, marginTop: -20, gap: 10 },
  statCard: {
    flex: 1, backgroundColor: theme.surface, borderRadius: 14, padding: 16,
    alignItems: 'center', borderWidth: 1, borderColor: theme.border,
    shadowColor: '#000', shadowOpacity: 0.05, shadowRadius: 8, elevation: 3,
  },
  statLabel: { fontSize: 11, color: theme.textMuted, marginTop: 6, fontWeight: '600' },
  section: { paddingHorizontal: 20, marginTop: 28 },
  sectionHeader: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginBottom: 16 },
  sectionTitle: { fontSize: 18, fontWeight: '800', color: theme.text },
  seeAll: { fontSize: 14, color: theme.accent, fontWeight: '600' },
  serviceCard: {
    flexDirection: 'row', alignItems: 'center', backgroundColor: theme.surface,
    borderRadius: 14, padding: 16, marginBottom: 12,
    borderWidth: 1, borderColor: theme.border,
  },
  serviceIcon: {
    width: 48, height: 48, borderRadius: 12, backgroundColor: `${theme.accent}15`,
    justifyContent: 'center', alignItems: 'center', marginRight: 14,
  },
  serviceInfo: { flex: 1 },
  serviceName: { fontSize: 15, fontWeight: '700', color: theme.text },
  serviceTime: { fontSize: 12, color: theme.textMuted, marginTop: 2 },
  servicePrice: { fontSize: 16, fontWeight: '800', color: theme.accent },
  stepRow: {
    flexDirection: 'row', alignItems: 'center', gap: 12,
    backgroundColor: theme.surface, borderRadius: 12, padding: 14,
    marginBottom: 10, borderWidth: 1, borderColor: theme.border,
  },
  stepNum: {
    width: 28, height: 28, borderRadius: 14, backgroundColor: theme.accent,
    justifyContent: 'center', alignItems: 'center',
  },
  stepNumText: { color: '#fff', fontSize: 13, fontWeight: '700' },
  stepIcon: {
    width: 36, height: 36, borderRadius: 10, backgroundColor: `${theme.accent}15`,
    justifyContent: 'center', alignItems: 'center',
  },
  stepTitle: { fontSize: 14, fontWeight: '700', color: theme.text },
  stepDesc: { fontSize: 12, color: theme.textMuted },
});
