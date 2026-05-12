import React from 'react';
import { View, Text, ScrollView, TouchableOpacity, StyleSheet } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { useTheme } from '../contexts/ThemeContext';

export default function ServiceDetailScreen({ route, navigation }) {
  const { service } = route.params;
  const { theme } = useTheme();
  const s = styles(theme);

  const requirements = service.requirements ? service.requirements.split('\n').filter(Boolean) : [];

  return (
    <ScrollView style={s.container}>
      <View style={s.header}>
        <View style={s.iconWrap}><Ionicons name="document-text" size={32} color={theme.accent} /></View>
        <Text style={s.title}>{service.title}</Text>
        <Text style={s.time}><Ionicons name="time-outline" size={14} /> {service.processing_time}</Text>
      </View>

      {service.description ? (
        <View style={s.section}>
          <Text style={s.sectionTitle}>Description</Text>
          <Text style={s.desc}>{service.description}</Text>
        </View>
      ) : null}

      {requirements.length > 0 && (
        <View style={s.section}>
          <Text style={s.sectionTitle}>Requirements</Text>
          {requirements.map((req, i) => (
            <View key={i} style={s.reqRow}>
              <Ionicons name="checkmark-circle" size={18} color={theme.green} />
              <Text style={s.reqText}>{req}</Text>
            </View>
          ))}
        </View>
      )}

      <View style={s.section}>
        <Text style={s.sectionTitle}>Pricing</Text>
        <View style={s.priceCard}>
          <View style={s.priceRow}>
            <Text style={s.priceLabel}>Standard</Text>
            <Text style={s.priceValue}>₦{Number(service.price).toLocaleString()}</Text>
          </View>
          {service.fast_track_price ? (
            <View style={s.priceRow}>
              <Text style={[s.priceLabel, { color: theme.orange }]}><Ionicons name="flash" size={14} /> Fast-Track</Text>
              <Text style={s.priceValue}>₦{Number(service.fast_track_price).toLocaleString()}</Text>
            </View>
          ) : null}
          {service.delivery_fee ? (
            <View style={s.priceRow}>
              <Text style={[s.priceLabel, { color: theme.accent }]}><Ionicons name="bicycle" size={14} /> Delivery</Text>
              <Text style={s.priceValue}>₦{Number(service.delivery_fee).toLocaleString()}</Text>
            </View>
          ) : null}
        </View>
      </View>

      <TouchableOpacity style={s.btn} onPress={() => navigation.navigate('NewOrder', { serviceId: service.id })}>
        <Text style={s.btnText}>Get Started</Text>
        <Ionicons name="arrow-forward" size={18} color="#fff" />
      </TouchableOpacity>

      <View style={{ height: 40 }} />
    </ScrollView>
  );
}

const styles = (theme) => StyleSheet.create({
  container: { flex: 1, backgroundColor: theme.bg, padding: 20 },
  header: { alignItems: 'center', marginBottom: 28 },
  iconWrap: {
    width: 72, height: 72, borderRadius: 20, backgroundColor: `${theme.accent}15`,
    justifyContent: 'center', alignItems: 'center', marginBottom: 16,
  },
  title: { fontSize: 22, fontWeight: '800', color: theme.text, textAlign: 'center' },
  time: { fontSize: 14, color: theme.textMuted, marginTop: 8 },
  section: { marginBottom: 24 },
  sectionTitle: { fontSize: 16, fontWeight: '700', color: theme.text, marginBottom: 12 },
  desc: { fontSize: 14, color: theme.textSecondary, lineHeight: 22 },
  reqRow: { flexDirection: 'row', alignItems: 'center', gap: 10, marginBottom: 8 },
  reqText: { fontSize: 14, color: theme.textSecondary, flex: 1 },
  priceCard: { backgroundColor: theme.bgSecondary, borderRadius: 14, padding: 16, borderWidth: 1, borderColor: theme.border },
  priceRow: { flexDirection: 'row', justifyContent: 'space-between', paddingVertical: 10, borderBottomWidth: 1, borderBottomColor: theme.border },
  priceLabel: { fontSize: 14, color: theme.textSecondary, fontWeight: '500' },
  priceValue: { fontSize: 16, fontWeight: '700', color: theme.text },
  btn: {
    backgroundColor: theme.accent, borderRadius: 14, padding: 18,
    flexDirection: 'row', justifyContent: 'center', alignItems: 'center', gap: 8,
  },
  btnText: { color: '#fff', fontSize: 17, fontWeight: '700' },
});
