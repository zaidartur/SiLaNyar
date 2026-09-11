import fs from 'fs';
import { execSync } from 'child_process';

const testCss = `
@layer properties, theme, base, vuetify-core, vuetify-components, vuetify-overrides, vuetify-utilities, utilities;
@import "tailwindcss";

@layer vuetify-components {
  .v-btn {
    background-color: #1b5e20;
    color: white;
  }
  .v-field__outline {
    border: 1px solid #ccc;
  }
}
`;
fs.writeFileSync('temp_test.css', testCss);
try {
    const res = execSync('npx @tailwindcss/cli -i temp_test.css', { encoding: 'utf-8' });
    console.log('Success! Length:', res.length);
    const layerDecl = res.match(/@layer [^;]+;/);
    console.log('Layer declaration in output:', layerDecl ? layerDecl[0] : 'none');
    console.log('Contains v-btn in vuetify-components:', res.includes('.v-btn'));
} catch (e) {
    console.log('Error:', e.message);
} finally {
    if (fs.existsSync('temp_test.css')) fs.unlinkSync('temp_test.css');
}
