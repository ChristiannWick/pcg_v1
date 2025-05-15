<template>
    <div class="search-container">
        <v-text-field
            ref="searchField"
            clearable
            :value="value"
            :prepend-inner-icon="prependIcon"
            :hide-details="hideDetails"
            :dense="dense"
            :outlined="outlined"
            :placeholder="placeholder"
            @click:clear="resetSearch"
            @input="handleInput"
            @focus="showHistory = true"
            @blur.stop="hideHistory"
            @keydown.esc.stop="hideHistory"
        ></v-text-field>

        <v-card v-show="showHistory && searchHistory.length" class="history-dropdown">
        <v-list dense>
            <v-subheader>{{ historyTitle }}</v-subheader>
            <v-list-item
                v-for="(item, index) in searchHistory"
                :key="index"
                @click="applyHistoryItem(item)"
                >
                <v-list-item-avatar>
                    <v-icon>{{ historyIcon }}</v-icon>
                </v-list-item-avatar>
                <v-list-item-content>
                    <v-list-item-title>{{ item }}</v-list-item-title>
                </v-list-item-content>
                <!-- <v-list-item-action>
                    <v-btn @mousedown.prevent icon @click.stop="removeHistoryItem(index, $event)">
                    <v-icon small>mdi-close</v-icon>
                    </v-btn>
                </v-list-item-action> -->
            </v-list-item>
        </v-list>
        </v-card>
    </div>
</template>

<script>
export default {
    props: {
        value: {
            type: String,
            default: "",
        },
        prependIcon: {
            type: String,
            default: "mdi-magnify",
        },
        placeholder: {
            type: String,
            default: "Search here...",
        },
        historyTitle: {
            type: String,
            default: "Recent Searches",
        },
        historyIcon: {
            type: String,
            default: "mdi-history",
        },
        storageKey: {
            type: String,
            required: true,
        },
        hideDetails: {
            type: Boolean,
            default: true,
        },
        dense: {
            type: Boolean,
            default: true,
        },
        outlined: {
            type: Boolean,
            default: true,
        },
        debounceTime: {
            type: Number,
            default: 2000,
        },
        maxHistoryItems: {
            type: Number,
            default: 15,
        },
    },

    data() {
        return {
            internalValue: this.value,
            searchHistory: [],
            showHistory: false,
            typingTimer: null,
        };
    },

    watch: {
        value(newVal) {
            console.log(newVal, "newval test");
            this.internalValue = newVal;
        },
    },

    created() {
        const history = localStorage.getItem(this.storageKey);
        this.searchHistory = history ? JSON.parse(history) : [];
    },

    methods: {
        handleInput(value) {
        this.$emit("input", value); // Emit input event for v-model
        this.handleTyping(value);
        },

        handleTyping(value) {
            clearTimeout(this.typingTimer);
            if (value) {
                this.typingTimer = setTimeout(() => {
                this.saveSearch(value.trim());
                }, this.debounceTime);
            }
        },

        saveSearch(query) {
            if (!query) return;

            this.searchHistory = [
                query,
                ...this.searchHistory.filter((item) => item !== query),
            ].slice(0, this.maxHistoryItems);

            localStorage.setItem(this.storageKey, JSON.stringify(this.searchHistory));
            this.$emit("search", query);
        },

        resetSearch() {
            this.internalValue = "";
            this.$emit("input", "");
            clearTimeout(this.typingTimer);
            this.showHistory = true;
        },

        applyHistoryItem(item) {
            this.internalValue = item;
            this.$emit("input", item);
            this.$emit("search", item);
            this.showHistory = false;
        },

        removeHistoryItem(index, event) {
            if (event && event.preventDefault) {
                event.preventDefault();
                event.stopPropagation();
            }

            this.searchHistory = this.searchHistory.filter((_, i) => i !== index);
            localStorage.setItem(this.storageKey, JSON.stringify(this.searchHistory));
            this.showHistory = true;

            this.$nextTick(() => {
                if (this.$refs.searchField) {
                    this.$refs.searchField.focus();
                }
            });
        },

        hideHistory() {
            setTimeout(() => {
                this.showHistory = false;
            }, 200);
        },
    },

    beforeDestroy() {
        clearTimeout(this.typingTimer);
    },
};
</script>
