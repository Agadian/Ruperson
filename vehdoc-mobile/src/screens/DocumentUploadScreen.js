import React, { useState } from 'react';
import { View, Text, TouchableOpacity, StyleSheet, ScrollView, Alert, Image } from 'react-native';
import * as ImagePicker from 'expo-image-picker';
import * as DocumentPicker from 'expo-document-picker';
import { Ionicons } from '@expo/vector-icons';
import { useTheme } from '../contexts/ThemeContext';
import { uploadDocuments } from '../utils/api';

export default function DocumentUploadScreen({ route, navigation }) {
  const { orderId } = route.params;
  const { theme } = useTheme();
  const [files, setFiles] = useState([]);
  const [uploading, setUploading] = useState(false);

  const pickImage = async () => {
    const result = await ImagePicker.launchImageLibraryAsync({
      mediaTypes: ImagePicker.MediaTypeOptions.Images,
      allowsMultipleSelection: true,
      quality: 0.8,
    });
    if (!result.canceled) {
      setFiles(prev => [...prev, ...result.assets.map(a => ({ uri: a.uri, name: a.fileName || 'image.jpg', type: a.mimeType || 'image/jpeg' }))]);
    }
  };

  const pickDocument = async () => {
    const result = await DocumentPicker.getDocumentAsync({ type: 'application/pdf', multiple: true });
    if (!result.canceled) {
      setFiles(prev => [...prev, ...result.assets.map(a => ({ uri: a.uri, name: a.name, type: a.mimeType || 'application/pdf' }))]);
    }
  };

  const removeFile = (index) => {
    setFiles(prev => prev.filter((_, i) => i !== index));
  };

  const handleUpload = async () => {
    if (files.length === 0) { Alert.alert('Error', 'Please select files to upload'); return; }
    setUploading(true);
    try {
      const formData = new FormData();
      formData.append('order_id', orderId);
      files.forEach((file, i) => {
        formData.append(`documents[${i}]`, { uri: file.uri, name: file.name, type: file.type });
      });
      await uploadDocuments(orderId, formData);
      Alert.alert('Success', 'Documents uploaded successfully', [
        { text: 'OK', onPress: () => navigation.goBack() }
      ]);
    } catch (error) {
      Alert.alert('Error', error.response?.data?.message || 'Upload failed');
    } finally {
      setUploading(false);
    }
  };

  const s = styles(theme);

  return (
    <ScrollView style={s.container}>
      <Text style={s.title}>Upload Documents</Text>
      <Text style={s.subtitle}>Upload required documents for your order</Text>

      <View style={s.actions}>
        <TouchableOpacity style={s.pickBtn} onPress={pickImage}>
          <Ionicons name="image" size={28} color={theme.accent} />
          <Text style={s.pickText}>Photos</Text>
        </TouchableOpacity>
        <TouchableOpacity style={s.pickBtn} onPress={pickDocument}>
          <Ionicons name="document" size={28} color={theme.purple} />
          <Text style={s.pickText}>PDF Files</Text>
        </TouchableOpacity>
      </View>

      {files.length > 0 && (
        <View style={s.filesList}>
          {files.map((file, index) => (
            <View key={index} style={s.fileItem}>
              {file.type?.includes('image') ? (
                <Image source={{ uri: file.uri }} style={s.fileThumbnail} />
              ) : (
                <View style={s.fileIcon}><Ionicons name="document-text" size={24} color={theme.accent} /></View>
              )}
              <View style={{ flex: 1 }}>
                <Text style={s.fileName} numberOfLines={1}>{file.name}</Text>
                <Text style={s.fileType}>{file.type?.includes('pdf') ? 'PDF' : 'Image'}</Text>
              </View>
              <TouchableOpacity onPress={() => removeFile(index)}>
                <Ionicons name="close-circle" size={24} color={theme.red} />
              </TouchableOpacity>
            </View>
          ))}
        </View>
      )}

      <TouchableOpacity style={[s.uploadBtn, (uploading || files.length === 0) && { opacity: 0.5 }]}
        onPress={handleUpload} disabled={uploading || files.length === 0}>
        <Ionicons name="cloud-upload" size={20} color="#fff" />
        <Text style={s.uploadText}>{uploading ? 'Uploading...' : `Upload ${files.length} File(s)`}</Text>
      </TouchableOpacity>

      <View style={{ height: 40 }} />
    </ScrollView>
  );
}

const styles = (theme) => StyleSheet.create({
  container: { flex: 1, backgroundColor: theme.bg, padding: 20 },
  title: { fontSize: 22, fontWeight: '800', color: theme.text, marginBottom: 8 },
  subtitle: { fontSize: 14, color: theme.textSecondary, marginBottom: 24 },
  actions: { flexDirection: 'row', gap: 16, marginBottom: 24 },
  pickBtn: {
    flex: 1, backgroundColor: theme.surface, borderRadius: 16, padding: 24,
    alignItems: 'center', borderWidth: 2, borderColor: theme.border, borderStyle: 'dashed',
  },
  pickText: { fontSize: 14, fontWeight: '600', color: theme.text, marginTop: 8 },
  filesList: { marginBottom: 24 },
  fileItem: {
    flexDirection: 'row', alignItems: 'center', backgroundColor: theme.surface,
    borderRadius: 12, padding: 12, marginBottom: 8,
    borderWidth: 1, borderColor: theme.border,
  },
  fileThumbnail: { width: 48, height: 48, borderRadius: 8, marginRight: 12 },
  fileIcon: {
    width: 48, height: 48, borderRadius: 8, backgroundColor: `${theme.accent}15`,
    justifyContent: 'center', alignItems: 'center', marginRight: 12,
  },
  fileName: { fontSize: 14, fontWeight: '600', color: theme.text },
  fileType: { fontSize: 12, color: theme.textMuted },
  uploadBtn: {
    flexDirection: 'row', alignItems: 'center', justifyContent: 'center', gap: 8,
    backgroundColor: theme.accent, borderRadius: 14, padding: 18,
  },
  uploadText: { color: '#fff', fontSize: 16, fontWeight: '700' },
});
