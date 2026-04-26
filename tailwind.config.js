import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
	content: [
		'./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
		'./storage/framework/views/*.php',
		'./resources/views/**/*.blade.php',
		'./resources/js/**/*.js',
	],
	theme: {
		extend: {
			fontFamily: {
				heading: ['"Space Grotesk"', ...defaultTheme.fontFamily.sans],
				body: ['Inter', ...defaultTheme.fontFamily.sans],
			},
			colors: {
				brand: {
					DEFAULT: '#18A96B',
					light: '#22c97e',
					dark: '#0f7a4c',
				},
				surface: {
					DEFAULT: '#111827',
					dark: '#0D1117',
					light: '#1a2332',
				},
				'app-border': '#1F2D3D',
				success: '#18A96B',
				warning: '#EAB308',
				danger: '#EF4444',
				info: '#3B82F6',
				chart: {
					1: '#18A96B',
					2: '#3B82F6',
					3: '#EAB308',
					4: '#8B5CF6',
					5: '#EC4899',
				},
				muted: '#6B7280',
			},
		},
	},
	plugins: [],
};
