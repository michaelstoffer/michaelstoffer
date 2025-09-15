import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography'

export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/!(*.stories|*.spec).{js,ts,vue}',
    ],
    theme: {
        extend: {
            fontFamily: { sans: ['Inter', ...defaultTheme.fontFamily.sans] },
            colors: {
                brand: {
                    ink: '#0f172a',     // slate-900
                    soft: '#475569',    // slate-600
                    accent: '#f59e0b',  // amber-500
                },
                paper: '#f8fafc',     // slate-50
                panel: '#ffffff'
            },
            boxShadow: {
                soft: '0 1px 2px rgba(0,0,0,0.04), 0 8px 24px rgba(0,0,0,0.06)'
            }
        },
    },
    plugins: [forms, typography],
}
