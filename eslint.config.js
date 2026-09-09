import js from "@eslint/js";
import tseslint from "@typescript-eslint/eslint-plugin";
import tsparser from "@typescript-eslint/parser";
import vue from "eslint-plugin-vue";
import vueParser from "vue-eslint-parser";
import globals from "globals";

export default [
  js.configs.recommended,
  {
    languageOptions: {
      globals: {
        ...globals.browser,
        ...globals.node,
      },
    },
  },
  {
    files: ["**/*.ts", "**/*.tsx"],
    languageOptions: {
      parser: tsparser,
      parserOptions: {
        project: './tsconfig.json',
      },
    },
    plugins: {
      "@typescript-eslint": tseslint,
    },
    rules: {
      "@typescript-eslint/no-unused-vars": "warn",
    },
  },
  {
    files: ["**/*.vue"],
    plugins: {
      vue,
    },
    languageOptions: {
      parser: vueParser, // gunakan vue-eslint-parser
      parserOptions: {
        parser: tsparser, // gunakan tsparser untuk <script lang="ts">
        ecmaVersion: 2020,
        sourceType: "module",
        extraFileExtensions: [".vue"],
        project: './tsconfig.json',
      },
    },
    rules: {
      "vue/no-unused-vars": "warn",
    },
  },
];