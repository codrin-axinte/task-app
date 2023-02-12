<script setup>
import {ref, computed} from 'vue'
import {CheckIcon, ChevronUpDownIcon} from '@heroicons/vue/20/solid'
import {
    Combobox,
    ComboboxInput,
    ComboboxOptions,
    ComboboxOption,
    ComboboxButton
} from '@headlessui/vue'

const props = defineProps({options: Array, modelValue: Object})
const emit = defineEmits(['update:modelValue'])

const query = ref('')

const filteredOptions = computed(() =>
    query.value === ''
        ? props.options
        : props.options.filter((option) => {
            return option.label.toLowerCase().includes(query.value.toLowerCase())
        })
)

function onUpdate(value) {
    emit('update:modelValue', value);
}

function clear() {
    query.value = '';
    emit('update:modelValue', null);
}

</script>

<template>
    <Combobox as="div"
              :modelValue="modelValue"
              @update:modelValue="onUpdate"
              by="value"
              nullable>
        <div class="relative mt-1">
            <ComboboxInput
                class="w-full rounded-lg border border-base-100 bg-base-300 py-2 pl-3 pr-10 shadow-sm focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary sm:text-sm transition"
                @change="query = $event.target.value" :displayValue="(option) => option?.label"/>

            <ComboboxButton class="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none">
                <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true"/>
            </ComboboxButton>


            <transition
                enter-active-class="transition duration-100 ease-out"
                enter-from-class="transform scale-95 opacity-0"
                enter-to-class="transform scale-100 opacity-100"
                leave-active-class="transition duration-75 ease-out"
                leave-from-class="transform scale-100 opacity-100"
                leave-to-class="transform scale-95 opacity-0"
            >

                <ComboboxOptions v-if="filteredOptions.length > 0"
                                 class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-lg bg-base-300 py-1 text-base-content shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">

                    <ComboboxOption value="" v-slot="{ active, selected }">
                        <li :class="['relative cursor-default select-none py-2 pl-3 pr-9', active ? 'bg-base-100 text-white' : 'text-base-content']">
                            <span :class="['block truncate', selected && 'font-semibold']">
                              --
                            </span>

                            <span v-if="selected"
                                  :class="['absolute inset-y-0 right-0 flex items-center pr-4', active ? 'text-base-content' : 'text-primary']">
                          <CheckIcon class="h-5 w-5" aria-hidden="true"/>
                        </span>
                        </li>
                    </ComboboxOption>
                    <ComboboxOption v-for="(option, key) in filteredOptions" :key="key" :value="option" as="template"
                                    v-slot="{ active, selected }">
                        <li :class="['relative cursor-default select-none py-2 pl-3 pr-9', active ? 'bg-base-100 text-white' : 'text-base-content']">
                            <span :class="['block truncate', selected && 'font-semibold']">
                              {{ option.label }}
                            </span>

                            <span v-if="selected"
                                  :class="['absolute inset-y-0 right-0 flex items-center pr-4', active ? 'text-base-content' : 'text-primary']">
                          <CheckIcon class="h-5 w-5" aria-hidden="true"/>
                        </span>
                        </li>
                    </ComboboxOption>
                </ComboboxOptions>
            </transition>
        </div>

        <div class="mt-1 px-2 flex justify-end">
            <button type="button" @click="clear" class="text-gray-400">Clear</button>
        </div>
    </Combobox>
</template>


