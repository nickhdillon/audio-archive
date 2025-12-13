<div>
    <flux:subheading>Equalizer</flux:subheading>
            
    <flux:card class="p-5 mt-3 dark:bg-neutral-800!">
        <div x-data="equalizer(@js($user->eq_values), @js($this->presets))">
            <div class="flex items-center w-full gap-2">
                <flux:subheading class="text-neutral-800 dark:text-white!">
                    Presets
                </flux:subheading>

                <flux:select
                    size="sm"
                    class="w-50!" 
                    x-on:change="$dispatch('applyPreset')"
                    x-model='$wire.preset'
                >
                    <flux:select.option x-cloak x-show="$wire.preset === 'Manual'">
                        Manual
                    </flux:select.option>

                    @foreach ($this->presets as $preset)
                        <flux:select.option value="{{ $preset->id }}">
                            {{ $preset->name }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <div x-cloak x-show="!$wire.is_system_preset" class="ml-auto">
                    @if ($is_user_preset) 
                        <flux:button
                            icon="trash"
                            variant="ghost"
                            size="sm"
                            wire:click='deletePreset'
                        />
                    @elseif (! is_null($user->eq_values))
                        <flux:modal.trigger name="save-preset">
                            <flux:button
                                icon="save"
                                variant="ghost"
                                size="sm"
                            />
                        </flux:modal.trigger>

                        <flux:modal name="save-preset" class="w-86 sm:w-full">
                            <form wire:submit='saveAsPreset' class="space-y-6">
                                <flux:heading size="lg" class="font-semibold -mt-1.5!">
                                    Save Preset
                                </flux:heading>

                                <flux:field>
                                    <flux:label>Name</flux:label>

                                    <flux:input type="text" wire:model='name' required />

                                    <flux:error name="name" />
                                </flux:field>

                                <div class="flex">
                                    <div class="ml-auto flex gap-2">
                                        <flux:modal.close>
                                            <flux:button variant="ghost" size="sm">
                                                Cancel
                                            </flux:button>
                                        </flux:modal.close>

                                        <flux:button type="submit" variant="primary" size="sm">
                                            Save
                                        </flux:button>
                                    </div>
                                </div>
                            </form>
                        </flux:modal>
                    @endif
                </div>
            </div>

            <div class="flex items-start mt-8 mb-4 gap-2 sm:gap-4">
                <div class="hidden sm:flex flex-col h-40 justify-between">
                    <flux:text class="text-xs sm:text-sm">+12dB</flux:text>
                    <flux:text class="text-xs sm:text-sm">-12dB</flux:text>
                </div>

                <div class="relative w-full flex gap-2">
                    <div
                        class="absolute left-0 right-0 h-px bg-neutral-200 dark:bg-neutral-700"
                        :style="`bottom: ${zeroLine()}%`"
                    ></div>

                    <template x-for="(band, index) in frequencies" :key="index">
                        <div class="flex w-full flex-col items-center">
                            <div class="relative h-40 flex items-end">
                                <div class="absolute left-1/2 -translate-x-1/2 w-1.5 bg-neutral-300 dark:bg-neutral-600 h-full rounded-full"></div>
                                <div
                                    class="absolute -translate-x-1/2 w-1.5 bg-amber-400 rounded-full"
                                    :style="`height: ${barHeight(index)}%`"
                                ></div>

                                <div
                                    class="absolute left-1/2 -translate-x-1/2 w-4 h-4 bg-neutral-700 dark:bg-neutral-100 rounded-full cursor-pointer shadow-2xl"
                                    :style="`bottom: calc(${barHeight(index)}% - 8px)`"
                                ></div>

                                <input
                                    class="absolute inset-0 opacity-0 cursor-pointer"
                                    style="writing-mode: bt-lr;"
                                    type="range"
                                    :min="index === 0 ? -6 : -12"
                                    :max="index === 0 ? 6 : 12"
                                    step="0.1"
                                    x-model.number="gains[index]"
                                    x-on:input="updateFilter(index)"
                                    x-on:mouseup="saveEQ"
                                />
                            </div>

                            <flux:text
                                class="mt-2 text-xs sm:text-sm"
                                x-text="formatFrequency(band)">
                            </flux:text>
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex justify-end">
                <flux:button wire:click='resetPreset' variant="outline" size="sm">
                    Reset
                </flux:button>
            </div>
        </div>
    </flux:card>
</div>
