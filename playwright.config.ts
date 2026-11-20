import path from 'node:path';
import { defineConfig, devices } from '@playwright/test';

const port = Number( process.env.PLAYGROUND_PORT || 9400 );
const baseURL = `http://127.0.0.1:${ port }`;
const themeDir = __dirname;
const blueprintPath = path.join( __dirname, 'tests/e2e/blueprint.json' );

export default defineConfig( {
	testDir: './tests/e2e',
	fullyParallel: false,
	forbidOnly: !! process.env.CI,
	retries: process.env.CI ? 2 : 0,
	workers: 1,
	reporter: process.env.CI
		? 'list'
		: [ [ 'list' ], [ 'html', { open: 'never' } ] ],
	timeout: 120_000,
	expect: {
		timeout: 30_000,
	},
	use: {
		baseURL,
		screenshot: 'only-on-failure',
		trace: 'on-first-retry',
	},
	projects: [
		{
			name: 'chromium',
			use: { ...devices[ 'Desktop Chrome' ] },
		},
	],
	webServer: {
		command: `npx wp-playground-cli server --port=${ port } --mount="${ themeDir }:/wordpress/wp-content/themes/terminal-blog-dev" --blueprint="${ blueprintPath }"`,
		url: baseURL,
		timeout: 120_000,
		reuseExistingServer: ! process.env.CI,
		stdout: 'pipe',
		stderr: 'pipe',
	},
} );
