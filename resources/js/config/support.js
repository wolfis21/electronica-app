// Configuración de URLs para soporte técnico
export const supportConfig = {
    // URLs de Google Forms (reemplaza con tus URLs reales)
    bugReportUrl: 'https://forms.google.com/tu-formulario-bugs-aqui',
    suggestionUrl: 'https://forms.google.com/tu-formulario-sugerencias-aqui',
    
    // Información adicional que se puede incluir en los reportes
    getSystemInfo: () => {
        return {
            userAgent: navigator.userAgent,
            url: window.location.href,
            timestamp: new Date().toISOString(),
            screenResolution: `${screen.width}x${screen.height}`,
            language: navigator.language
        };
    },
    
    // Función para generar URLs con parámetros pre-llenados
    generateBugReportUrl: (additionalData = {}) => {
        const systemInfo = supportConfig.getSystemInfo();
        const params = new URLSearchParams({
            // Estos parámetros dependerán de cómo configures tu Google Form
            'entry.url': systemInfo.url,
            'entry.timestamp': systemInfo.timestamp,
            'entry.browser': systemInfo.userAgent,
            'entry.resolution': systemInfo.screenResolution,
            ...additionalData
        });
        
        return `${supportConfig.bugReportUrl}?${params.toString()}`;
    },
    
    generateSuggestionUrl: (additionalData = {}) => {
        const systemInfo = supportConfig.getSystemInfo();
        const params = new URLSearchParams({
            'entry.url': systemInfo.url,
            'entry.timestamp': systemInfo.timestamp,
            ...additionalData
        });
        
        return `${supportConfig.suggestionUrl}?${params.toString()}`;
    }
};
