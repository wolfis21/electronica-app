    <script setup>
    import { computed } from 'vue';

    const props = defineProps({
        modelValue: [String, Number, Array],
        options: {
            type: [Array, Object],
            required: true,
        },
        id: String,
        name: String,
        required: {
            type: Boolean,
            default: false,
        },
        disabled: {
            type: Boolean,
            default: false,
        },
    });

    const emit = defineEmits(['update:modelValue']);

    const proxyValue = computed({
        get() {
            return props.modelValue;
        },
        set(val) {
            emit('update:modelValue', val);
        },
    });

    const isOptionGroup = (option) => {
        return typeof option === 'object' && option !== null && option.hasOwnProperty('label') && option.hasOwnProperty('options');
    };

    const getOptionValue = (option) => {
        return typeof option === 'object' && option !== null && option.hasOwnProperty('value') ? option.value : option;
    };

    const getOptionLabel = (option) => {
        return typeof option === 'object' && option !== null && option.hasOwnProperty('label') ? option.label : option;
    };
    </script>

    <template>
        <select
            :id="id"
            :name="name"
            :value="modelValue"
            @change="event => proxyValue = event.target.value"
            :required="required"
            :disabled="disabled"
            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        >
            <option value="" disabled selected v-if="!modelValue && !required">Selecciona una opción</option>
            <template v-for="(option, index) in options" :key="index">
                <optgroup v-if="isOptionGroup(option)" :label="option.label">
                    <option v-for="(opt, i) in option.options" :key="i" :value="getOptionValue(opt)">
                        {{ getOptionLabel(opt) }}
                    </option>
                </optgroup>
                <option v-else :value="getOptionValue(option)">
                    {{ getOptionLabel(option) }}
                </option>
            </template>
        </select>
    </template>
    