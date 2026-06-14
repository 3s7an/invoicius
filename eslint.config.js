import pluginVue from 'eslint-plugin-vue'
import tseslint from 'typescript-eslint'

export default tseslint.config(
    // Register plugins globally so shared rules block can reference them
    { plugins: { '@typescript-eslint': tseslint.plugin, vue: pluginVue } },

    // .ts files — full TypeScript-ESLint recommended
    {
        files: ['**/*.ts'],
        extends: tseslint.configs.recommended,
    },

    // .vue files — vue-eslint-parser as main parser, @typescript-eslint/parser for <script>
    {
        files: ['**/*.vue'],
        extends: pluginVue.configs['flat/recommended'],
        languageOptions: {
            parserOptions: {
                parser: tseslint.parser,
            },
        },
    },

    // Shared rule overrides
    {
        rules: {
            'vue/multi-word-component-names': 'off',
            '@typescript-eslint/no-explicit-any': 'warn',
            // Project uses 4-space indentation — keep formatting out of ESLint
            'vue/html-indent': 'off',
            'vue/max-attributes-per-line': 'off',
            'vue/attribute-hyphenation': 'off',
            'vue/attributes-order': 'off',
            'vue/singleline-html-element-content-newline': 'off',
            'vue/first-attribute-linebreak': 'off',
            // Inertia useForm() returns a mutable proxy; direct mutation is intentional
            'vue/no-mutating-props': 'warn',
            'vue/prop-name-casing': 'off',
        },
    },
)
