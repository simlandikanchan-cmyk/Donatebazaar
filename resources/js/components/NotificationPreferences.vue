<template>
  <div class="notification-preferences">
    <div class="np-header">
      <h2>Notification Settings</h2>
      <p class="np-subtitle">Choose how and when you receive notifications</p>
    </div>

    <div v-if="loading" class="np-loading">
      <div class="np-spinner"></div>
      <span>Loading preferences...</span>
    </div>

    <div v-else-if="error" class="np-error">
      <p>{{ error }}</p>
      <button @click="fetchPreferences" class="np-btn np-btn-secondary">Retry</button>
    </div>

    <template v-else>
      <div class="np-controls">
        <button @click="enableAll" class="np-btn np-btn-outline" :disabled="saving">
          Enable All
        </button>
        <button @click="disableAll" class="np-btn np-btn-outline" :disabled="saving">
          Disable All
        </button>
        <button @click="resetDefaults" class="np-btn np-btn-outline" :disabled="saving">
          Reset to Defaults
        </button>
      </div>

      <div v-for="(group, type) in preferences" :key="type" class="np-group">
        <div class="np-group-header">
          <h3>{{ group.label }}</h3>
          <p class="np-desc">{{ group.description }}</p>
        </div>

        <div class="np-channels">
          <div v-for="(setting, channel) in group.channels" :key="channel" class="np-row">
            <label class="np-toggle-label">
              <input
                type="checkbox"
                :checked="setting.enabled"
                :disabled="!setting.can_be_disabled || saving"
                @change="togglePreference(type, channel, $event.target.checked)"
              />
              <span class="np-toggle-track">
                <span class="np-toggle-thumb"></span>
              </span>
              <span class="np-channel-name">{{ formatChannel(channel) }}</span>
            </label>

            <div v-if="setting.enabled" class="np-frequency">
              <select
                :value="setting.frequency"
                :disabled="saving"
                @change="updateFrequency(type, channel, $event.target.value)"
                class="np-select"
              >
                <option value="immediate">Immediate</option>
                <option value="daily">Daily Digest</option>
                <option value="weekly">Weekly Digest</option>
              </select>
            </div>

            <span v-if="!setting.can_be_disabled" class="np-badge">Required</span>
          </div>
        </div>
      </div>

      <div v-if="successMessage" class="np-success">{{ successMessage }}</div>
      <div v-if="errorMessage" class="np-error-msg">{{ errorMessage }}</div>

      <div class="np-actions">
        <button @click="saveAll" class="np-btn np-btn-primary" :disabled="saving">
          {{ saving ? 'Saving...' : 'Save Preferences' }}
        </button>
      </div>
    </template>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const preferences = ref({})
const loading = ref(true)
const saving = ref(false)
const error = ref(null)
const successMessage = ref('')
const errorMessage = ref('')
const changed = ref(false)

async function fetchPreferences() {
  loading.value = true
  error.value = null
  try {
    const res = await fetch('/api/v1/notification-preferences', {
      headers: { 'Accept': 'application/json' }
    })
    if (!res.ok) throw new Error('Failed to load preferences')
    const json = await res.json()
    preferences.value = json.data
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}

async function saveAll() {
  saving.value = true
  successMessage.value = ''
  errorMessage.value = ''
  try {
    const notifications = []
    for (const [type, group] of Object.entries(preferences.value)) {
      for (const [channel, setting] of Object.entries(group.channels)) {
        notifications.push({
          type,
          channel,
          enabled: setting.enabled,
          frequency: setting.frequency,
        })
      }
    }

    const res = await fetch('/api/v1/notification-preferences', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
      },
      body: JSON.stringify({ notifications }),
    })

    const json = await res.json()
    if (!res.ok) {
      errorMessage.value = json.message || 'Failed to save preferences'
      return
    }
    successMessage.value = 'Preferences saved successfully!'
    changed.value = false
    setTimeout(() => { successMessage.value = '' }, 3000)
  } catch (e) {
    errorMessage.value = e.message
  } finally {
    saving.value = false
  }
}

function togglePreference(type, channel, enabled) {
  if (!preferences.value[type]?.channels[channel]) return
  preferences.value[type].channels[channel].enabled = enabled
  changed.value = true
}

function updateFrequency(type, channel, frequency) {
  if (!preferences.value[type]?.channels[channel]) return
  preferences.value[type].channels[channel].frequency = frequency
  changed.value = true
}

function enableAll() {
  for (const group of Object.values(preferences.value)) {
    for (const setting of Object.values(group.channels)) {
      if (setting.can_be_disabled) {
        setting.enabled = true
      }
    }
  }
  changed.value = true
}

function disableAll() {
  for (const group of Object.values(preferences.value)) {
    for (const setting of Object.values(group.channels)) {
      if (setting.can_be_disabled) {
        setting.enabled = false
      }
    }
  }
  changed.value = true
}

async function resetDefaults() {
  saving.value = true
  try {
    const res = await fetch('/api/v1/notification-preferences/reset-all', {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
      },
    })
    if (!res.ok) throw new Error('Failed to reset preferences')
    await fetchPreferences()
    successMessage.value = 'Preferences reset to defaults!'
    changed.value = false
    setTimeout(() => { successMessage.value = '' }, 3000)
  } catch (e) {
    errorMessage.value = e.message
  } finally {
    saving.value = false
  }
}

function formatChannel(channel) {
  return channel === 'email' ? 'Email' : 'In-App'
}

onMounted(fetchPreferences)
</script>

<style scoped>
.notification-preferences {
  max-width: 720px;
  margin: 0 auto;
  font-family: 'DM Sans', sans-serif;
  color: #1c1917;
}

.np-header {
  margin-bottom: 28px;
}

.np-header h2 {
  font-size: 22px;
  font-weight: 700;
  color: #0c0a09;
  margin: 0 0 6px;
  font-family: 'DM Mono', monospace;
}

.np-subtitle {
  font-size: 14px;
  color: #78716c;
  margin: 0;
}

.np-loading {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 40px;
  justify-content: center;
  color: #78716c;
}

.np-spinner {
  width: 20px;
  height: 20px;
  border: 2px solid #e7e5e4;
  border-top-color: #2563eb;
  border-radius: 50%;
  animation: np-spin 0.6s linear infinite;
}

@keyframes np-spin {
  to { transform: rotate(360deg); }
}

.np-error {
  text-align: center;
  padding: 40px;
  color: #ef4444;
}

.np-controls {
  display: flex;
  gap: 10px;
  margin-bottom: 24px;
  flex-wrap: wrap;
}

.np-group {
  background: #fff;
  border: 1px solid #e7e5e4;
  border-radius: 12px;
  padding: 20px;
  margin-bottom: 16px;
}

.np-group-header {
  margin-bottom: 16px;
  padding-bottom: 12px;
  border-bottom: 1px solid #f5f5f4;
}

.np-group-header h3 {
  font-size: 15px;
  font-weight: 600;
  color: #0c0a09;
  margin: 0 0 4px;
}

.np-desc {
  font-size: 12.5px;
  color: #78716c;
  margin: 0;
}

.np-channels {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.np-row {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 8px 0;
}

.np-toggle-label {
  display: flex;
  align-items: center;
  gap: 10px;
  cursor: pointer;
  flex: 1;
}

.np-toggle-label input {
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}

.np-toggle-track {
  width: 36px;
  height: 20px;
  background: #d6d3d1;
  border-radius: 10px;
  position: relative;
  transition: background 0.2s;
  flex-shrink: 0;
}

.np-toggle-label input:checked + .np-toggle-track {
  background: #2563eb;
}

.np-toggle-label input:disabled + .np-toggle-track {
  opacity: 0.5;
  cursor: not-allowed;
}

.np-toggle-thumb {
  position: absolute;
  top: 2px;
  left: 2px;
  width: 16px;
  height: 16px;
  background: #fff;
  border-radius: 50%;
  transition: transform 0.2s;
  box-shadow: 0 1px 3px rgba(0,0,0,0.15);
}

.np-toggle-label input:checked + .np-toggle-track .np-toggle-thumb {
  transform: translateX(16px);
}

.np-channel-name {
  font-size: 13.5px;
  font-weight: 500;
  color: #292524;
}

.np-frequency {
  flex-shrink: 0;
}

.np-select {
  padding: 5px 10px;
  border: 1px solid #d6d3d1;
  border-radius: 8px;
  font-size: 12px;
  color: #292524;
  background: #fafaf9;
  cursor: pointer;
  outline: none;
}

.np-select:focus {
  border-color: #2563eb;
  box-shadow: 0 0 0 2px rgba(37,99,235,0.12);
}

.np-badge {
  font-size: 10px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  padding: 3px 8px;
  background: #fef3c7;
  color: #b45309;
  border-radius: 100px;
  flex-shrink: 0;
}

.np-success {
  background: #f0fdf4;
  border: 1px solid #bbf7d0;
  color: #15803d;
  padding: 10px 14px;
  border-radius: 10px;
  font-size: 13px;
  margin-bottom: 16px;
}

.np-error-msg {
  background: #fef2f2;
  border: 1px solid #fecaca;
  color: #dc2626;
  padding: 10px 14px;
  border-radius: 10px;
  font-size: 13px;
  margin-bottom: 16px;
}

.np-actions {
  margin-top: 24px;
}

.np-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 20px;
  border-radius: 999px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  border: none;
  font-family: 'DM Sans', sans-serif;
}

.np-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.np-btn-primary {
  background: linear-gradient(135deg, #2563eb, #0d9488);
  color: #fff;
  box-shadow: 0 4px 14px rgba(37,99,235,0.3);
}

.np-btn-primary:hover:not(:disabled) {
  opacity: 0.92;
  box-shadow: 0 6px 24px rgba(37,99,235,0.45);
}

.np-btn-secondary {
  background: #fff;
  color: #57534e;
  border: 1px solid #d6d3d1;
}

.np-btn-secondary:hover:not(:disabled) {
  background: #f5f5f4;
}

.np-btn-outline {
  background: #fff;
  color: #57534e;
  border: 1px solid #d6d3d1;
  font-size: 12px;
  padding: 8px 16px;
}

.np-btn-outline:hover:not(:disabled) {
  border-color: #2563eb;
  color: #2563eb;
  background: #eff6ff;
}
</style>
