import js from "@eslint/js";
import tseslint from "@typescript-eslint/eslint-plugin";
import tsparser from "@typescript-eslint/parser";
import vue from "eslint-plugin-vue";
import vueParser from "vue-eslint-parser"; // tambahkan ini

export default [
  js.configs.recommended,
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