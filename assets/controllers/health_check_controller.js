import { Controller } from '@hotwired/stimulus';

/*
 * Health Check Controller
 *
 * Stimulus controller that handles health check API calls
 * Following Symfony best practices for frontend interactivity
 */
export default class extends Controller {
    static targets = ['button', 'result'];

    connect() {
        console.log('Health Check controller connected!');
    }

    async check() {
        console.log('Check method called!');
        const button = this.buttonTarget;
        const resultDiv = this.resultTarget;

        // Disable button during request
        button.disabled = true;
        button.textContent = 'Checking...';

        try {
            const response = await fetch('/api/health', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest', // Symfony best practice
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            // Display success result
            resultDiv.innerHTML = `
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    <strong class="font-bold">✓ Health Check Successful</strong>
                    <div class="mt-2 text-sm">
                        <p><strong>Status:</strong> ${data.status}</p>
                        <p><strong>Service:</strong> ${data.service}</p>
                        <p><strong>Version:</strong> ${data.version}</p>
                        <p><strong>Timestamp:</strong> ${data.timestamp}</p>
                    </div>
                </div>
            `;

        } catch (error) {
            // Display error result
            resultDiv.innerHTML = `
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <strong class="font-bold">✗ Health Check Failed</strong>
                    <p class="mt-2">${error.message}</p>
                </div>
            `;
        } finally {
            // Re-enable button
            button.disabled = false;
            button.textContent = 'Check API Health';
        }
    }
}
