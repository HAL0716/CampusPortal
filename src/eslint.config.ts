import js from '@eslint/js';
import globals from 'globals';
import tseslint from 'typescript-eslint';
import react from 'eslint-plugin-react';
import reactHooks from 'eslint-plugin-react-hooks';
import eslintConfigPrettier from 'eslint-config-prettier/flat';
import simpleImportSort from 'eslint-plugin-simple-import-sort';

export default tseslint.config(
  // 🚫 ignore
  {
    ignores: ['node_modules', 'vendor', 'public/build', 'storage', 'bootstrap/cache'],
  },

  // JS基本ルール
  js.configs.recommended,

  // TypeScript
  ...tseslint.configs.recommended,

  // React + Inertia（フロント）
  {
    files: ['resources/js/**/*.{ts,tsx,js,jsx}'],

    languageOptions: {
      globals: {
        ...globals.browser,
        ...globals.node,
      },
    },

    plugins: {
      react,
      'react-hooks': reactHooks,
      'simple-import-sort': simpleImportSort,
    },

    settings: {
      react: {
        version: 'detect',
      },
    },

    rules: {
      ...react.configs.recommended.rules,
      ...reactHooks.configs.recommended.rules,
      'react/react-in-jsx-scope': 'off',

      'simple-import-sort/imports': 'error',
      'simple-import-sort/exports': 'error',
    },
  },

  eslintConfigPrettier,
);
