/**
 * Glass Effects JavaScript Framework
 * Sistema de abstracción para filtros SVG y efectos dinámicos
 * Basado en los glass-elements originales pero usando clases CSS
 */

class GlassEffects {
    constructor() {
        this.svgFilterSupport = this.detectSVGFilterSupport();
        this.svgContainer = this.createSVGContainer();
        this.filters = new Map();
        
        console.log(`[GlassEffects] SVG Filter Support: ${this.svgFilterSupport ? '✅ YES' : '❌ NO'}`);
        
        // Inicializar automáticamente los elementos existentes
        this.init();
    }

    /**
     * Detecta si el navegador soporta filtros SVG en backdrop-filter
     */
    detectSVGFilterSupport() {
        const testElement = document.createElement('div');
        testElement.style.backdropFilter = 'blur(1px)';
        
        if (!testElement.style.backdropFilter) {
            return false;
        }

        const userAgent = navigator.userAgent.toLowerCase();
        const isChrome = /chrome|chromium|crios|edg/.test(userAgent) && !/firefox|fxios/.test(userAgent);
        
        return isChrome;
    }

    /**
     * Crea el contenedor SVG para los filtros
     */
    createSVGContainer() {
        let container = document.getElementById('glass-svg-filters');
        
        if (!container) {
            container = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            container.id = 'glass-svg-filters';
            container.style.position = 'absolute';
            container.style.width = '0';
            container.style.height = '0';
            container.style.visibility = 'hidden';
            
            const defs = document.createElementNS('http://www.w3.org/2000/svg', 'defs');
            container.appendChild(defs);
            
            document.body.appendChild(container);
        }
        
        return container;
    }

    /**
     * Inicializar elementos con clase glass-advanced
     */
    init() {
        const advancedElements = document.querySelectorAll('.glass-advanced');
        advancedElements.forEach(element => this.enhanceElement(element));
        
        // Observer para nuevos elementos
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === Node.ELEMENT_NODE) {
                        if (node.classList && node.classList.contains('glass-advanced')) {
                            this.enhanceElement(node);
                        }
                        
                        // Buscar elementos hijos con glass-advanced
                        const childElements = node.querySelectorAll && node.querySelectorAll('.glass-advanced');
                        if (childElements) {
                            childElements.forEach(child => this.enhanceElement(child));
                        }
                    }
                });
            });
        });
        
        observer.observe(document.body, { childList: true, subtree: true });
    }

    /**
     * Mejora un elemento con efectos avanzados
     */
    enhanceElement(element) {
        if (element.dataset.glassEnhanced) return;
        
        const config = this.getElementConfig(element);
        
        if (this.svgFilterSupport) {
            this.applyAdvancedFilter(element, config);
        } else {
            this.applyFallbackFilter(element, config);
        }
        
        this.addInteractivity(element, config);
        
        // Si es auto-size, configurar observer para cambios de tamaño
        if (config.autoSize && this.svgFilterSupport) {
            this.setupAutoSizeObserver(element, config);
        }
        
        // Añadir efectos de hover solo si están explícitamente habilitados
        if (config.hoverEffects === true) {
            this.addHoverEffects(element, config);
            // Añadir clase CSS para los efectos de hover
            element.classList.add('with-hover-effects');
        }
        
        element.dataset.glassEnhanced = 'true';
    }

    /**
     * Configura observer para elementos con auto-size
     */
    setupAutoSizeObserver(element, config) {
        // Observer para cambios en el contenido
        const contentObserver = new MutationObserver(() => {
            // Pequeño delay para que el contenido se renderice
            setTimeout(() => {
                this.applyFilterWithAutoSize(element, config);
            }, 10);
        });
        
        contentObserver.observe(element, { 
            childList: true, 
            subtree: true, 
            characterData: true 
        });

        // ResizeObserver para cambios de tamaño si está disponible
        if (window.ResizeObserver) {
            const resizeObserver = new ResizeObserver(() => {
                this.applyFilterWithAutoSize(element, config);
            });
            resizeObserver.observe(element);
        }
    }

    /**
     * Obtiene la configuración de un elemento desde sus atributos data-*
     */
    getElementConfig(element) {
        return {
            width: parseInt(element.dataset.glassWidth) || this.getElementWidth(element),
            height: parseInt(element.dataset.glassHeight) || this.getElementHeight(element),
            radius: parseInt(element.dataset.glassRadius) || 16,
            depth: parseInt(element.dataset.glassDepth) || 10,
            blur: parseInt(element.dataset.glassBlur) || 2,
            strength: parseInt(element.dataset.glassStrength) || 100,
            chromaticAberration: parseInt(element.dataset.glassChromaticAberration) || 0,
            backgroundColor: element.dataset.glassBackgroundColor || 'rgba(255, 255, 255, 0.4)',
            autoSize: element.hasAttribute('data-glass-auto-size'),
            hoverEffects: element.dataset.glassHoverEffects === 'true' || element.classList.contains('with-hover-effects'),
            hoverFilterDisabled: element.dataset.glassHoverFilter !== 'false' // Por defecto true, se desactiva con data-glass-hover-filter="false"
        };
    }

    /**
     * Obtiene el ancho real del elemento
     */
    getElementWidth(element) {
        const rect = element.getBoundingClientRect();
        return Math.max(rect.width || 200, 50);
    }

    /**
     * Obtiene la altura real del elemento
     */
    getElementHeight(element) {
        const rect = element.getBoundingClientRect();
        return Math.max(rect.height || 200, 30);
    }

    /**
     * Aplica filtro avanzado con SVG usando la implementación original
     */
    applyAdvancedFilter(element, config) {
        // Aplicar estilos base primero
        element.style.background = config.backgroundColor;
        element.style.boxShadow = '1px 1px 1px 0px rgba(255,255,255, 0.60) inset, -1px -1px 1px 0px rgba(255,255,255, 0.60) inset, 0px 0px 16px 0px rgba(0,0,0, 0.04)';
        element.style.borderRadius = `${config.radius}px`;
        element.style.border = 'none';
        element.style.transition = 'transform 0.1s ease';
        
        if (config.autoSize) {
            element.style.display = 'inline-block';
            element.style.width = 'fit-content';
            element.style.padding = 'var(--glass-padding, 16px 24px)';
            
            // Para auto-size, esperar a que el elemento se renderice y luego aplicar el filtro
            this.applyFilterWithAutoSize(element, config);
        } else {
            element.style.width = `${config.width}px`;
            element.style.height = `${config.height}px`;
            element.style.display = 'flex';
            element.style.alignItems = 'center';
            element.style.justifyContent = 'center';
            
            // Aplicar filtro directamente para tamaño fijo
            const filterUrl = this.getDisplacementFilter(config);
            element.style.backdropFilter = `blur(${config.blur / 2}px) url('${filterUrl}') blur(${config.blur}px) brightness(1.1) saturate(1.5)`;
        }
    }

    /**
     * Aplica filtro SVG para elementos con auto-size
     */
    applyFilterWithAutoSize(element, config) {
        // Usar doble requestAnimationFrame para asegurar que el layout esté completo
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                // Forzar reflow para obtener dimensiones exactas
                element.offsetWidth;
                element.offsetHeight;
                
                // Obtener dimensiones reales después del renderizado
                const rect = element.getBoundingClientRect();
                let actualWidth = Math.ceil(rect.width);
                let actualHeight = Math.ceil(rect.height);
                
                // Validar que las dimensiones sean válidas
                if (actualWidth <= 0 || actualHeight <= 0) {
                    // Si aún no hay dimensiones, reintentar con un delay mayor
                    setTimeout(() => this.applyFilterWithAutoSize(element, config), 50);
                    return;
                }
                
                // Asegurar tamaños mínimos razonables para el filtro SVG
                actualWidth = Math.max(actualWidth, 50);
                actualHeight = Math.max(actualHeight, 30);
                
                // Verificar si las dimensiones han cambiado significativamente
                const lastWidth = parseInt(element.dataset.lastFilterWidth) || 0;
                const lastHeight = parseInt(element.dataset.lastFilterHeight) || 0;
                
                if (Math.abs(actualWidth - lastWidth) < 2 && Math.abs(actualHeight - lastHeight) < 2) {
                    // Las dimensiones no han cambiado significativamente, no actualizar
                    return;
                }
                
                // Guardar las nuevas dimensiones
                element.dataset.lastFilterWidth = actualWidth;
                element.dataset.lastFilterHeight = actualHeight;
                
                // Crear configuración con las dimensiones reales
                const autoSizeConfig = {
                    ...config,
                    width: actualWidth,
                    height: actualHeight
                };
                
                // Aplicar el filtro SVG con las dimensiones correctas
                const filterUrl = this.getDisplacementFilter(autoSizeConfig);
                element.style.backdropFilter = `blur(${config.blur / 2}px) url('${filterUrl}') blur(${config.blur}px) brightness(1.1) saturate(1.5)`;
            });
        });
    }

    /**
     * Aplica filtro de respaldo
     */
    applyFallbackFilter(element, config) {
        // Fallback para navegadores sin soporte SVG
        element.style.backdropFilter = `blur(${config.blur * 2}px)`;
        element.style.background = config.backgroundColor;
        element.style.boxShadow = '1px 1px 1px 0px rgba(255,255,255, 0.60) inset, -1px -1px 1px 0px rgba(255,255,255, 0.60) inset, 0px 0px 16px 0px rgba(0,0,0, 0.04)';
        element.style.borderRadius = `${config.radius}px`;
        element.style.border = '1px solid rgba(255, 255, 255, 0.3)';
        element.style.cursor = 'pointer';
        element.style.transition = 'transform 0.1s ease';
        
        if (config.autoSize) {
            element.style.display = 'inline-block';
            element.style.width = 'fit-content';
            element.style.padding = 'var(--glass-padding, 16px 24px)';
        } else {
            element.style.width = `${config.width}px`;
            element.style.height = `${config.height}px`;
            element.style.display = 'flex';
            element.style.alignItems = 'center';
            element.style.justifyContent = 'center';
        }
    }

    /**
     * Crea el mapa de desplazamiento (copiado del original)
     */
    getDisplacementMap({ height, width, radius, depth }) {
        const svg = `<svg height="${height}" width="${width}" viewBox="0 0 ${width} ${height}" xmlns="http://www.w3.org/2000/svg">
            <style>
                .mix { mix-blend-mode: screen; }
            </style>
            <defs>
                <linearGradient 
                  id="Y" 
                  x1="0" 
                  x2="0" 
                  y1="${Math.ceil((radius / height) * 15)}%" 
                  y2="${Math.floor(100 - (radius / height) * 15)}%">
                    <stop offset="0%" stop-color="#0F0" />
                    <stop offset="100%" stop-color="#000" />
                </linearGradient>
                <linearGradient 
                  id="X" 
                  x1="${Math.ceil((radius / width) * 15)}%" 
                  x2="${Math.floor(100 - (radius / width) * 15)}%"
                  y1="0" 
                  y2="0">
                    <stop offset="0%" stop-color="#F00" />
                    <stop offset="100%" stop-color="#000" />
                </linearGradient>
            </defs>
    
            <rect x="0" y="0" height="${height}" width="${width}" fill="#808080" />
            <g filter="blur(2px)">
              <rect x="0" y="0" height="${height}" width="${width}" fill="#000080" />
              <rect
                  x="0"
                  y="0"
                  height="${height}"
                  width="${width}"
                  fill="url(#Y)"
                  class="mix"
              />
              <rect
                  x="0"
                  y="0"
                  height="${height}"
                  width="${width}"
                  fill="url(#X)"
                  class="mix"
              />
              <rect
                  x="${depth}"
                  y="${depth}"
                  height="${height - 2 * depth}"
                  width="${width - 2 * depth}"
                  fill="#808080"
                  rx="${radius}"
                  ry="${radius}"
                  filter="blur(${depth}px)"
              />
            </g>
        </svg>`;
    
        return "data:image/svg+xml;utf8," + encodeURIComponent(svg);
    }

    /**
     * Crea el filtro de desplazamiento (copiado del original)
     */
    getDisplacementFilter(config) {
        const { height, width, radius, depth, strength = 100, chromaticAberration = 0 } = config;
        const displacementMapUrl = this.getDisplacementMap({ height, width, radius, depth });
        
        const svg = `<svg height="${height}" width="${width}" viewBox="0 0 ${width} ${height}" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <filter id="displace" color-interpolation-filters="sRGB">
                    <feImage x="0" y="0" height="${height}" width="${width}" href="${displacementMapUrl}" result="displacementMap" />
                    <feDisplacementMap
                        transform-origin="center"
                        in="SourceGraphic"
                        in2="displacementMap"
                        scale="${strength + chromaticAberration * 2}"
                        xChannelSelector="R"
                        yChannelSelector="G"
                    />
                    <feColorMatrix
                    type="matrix"
                    values="1 0 0 0 0
                            0 0 0 0 0
                            0 0 0 0 0
                            0 0 0 1 0"
                    result="displacedR"
                            />
                    <feDisplacementMap
                        in="SourceGraphic"
                        in2="displacementMap"
                        scale="${strength + chromaticAberration}"
                        xChannelSelector="R"
                        yChannelSelector="G"
                    />
                    <feColorMatrix
                    type="matrix"
                    values="0 0 0 0 0
                            0 1 0 0 0
                            0 0 0 0 0
                            0 0 0 1 0"
                    result="displacedG"
                            />
                    <feDisplacementMap
                            in="SourceGraphic"
                            in2="displacementMap"
                            scale="${strength}"
                            xChannelSelector="R"
                            yChannelSelector="G"
                        />
                        <feColorMatrix
                        type="matrix"
                        values="0 0 0 0 0
                                0 0 0 0 0
                                0 0 1 0 0
                                0 0 0 1 0"
                        result="displacedB"
                                />
                      <feBlend in="displacedR" in2="displacedG" mode="screen"/>
                      <feBlend in2="displacedB" mode="screen"/>
                </filter>
            </defs>
        </svg>`;
    
        return "data:image/svg+xml;utf8," + encodeURIComponent(svg) + "#displace";
    }

    /**
     * Añade interactividad al elemento (coordinada con hover effects)
     */
    addInteractivity(element, config) {
        // Guardar estado de interactividad en el elemento
        element._glassState = {
            isPressed: false,
            isHovering: false,
            originalDepth: config.depth,
            hoverIntensity: 0
        };
        
        const updateFilterState = () => {
            if (!this.svgFilterSupport) {
                // Fallback simple sin filtro SVG
                const isPressed = element._glassState.isPressed;
                const intensity = element._glassState.hoverIntensity || 0;
                
                if (isPressed) {
                    element.style.transform = 'scale(0.98)';
                } else {
                    const translateY = -2 * intensity;
                    const scale = 1 + 0.02 * intensity;
                    element.style.transform = `translateY(${translateY}px) scale(${scale})`;
                }
                return;
            }
            
            // Calcular valores basados en estado
            let depth = element._glassState.originalDepth;
            let strength = config.strength;
            let blur = config.blur;
            let brightness = 1.1;
            let saturate = 1.5;
            
            // Aplicar efecto de hover si está activo
            const intensity = element._glassState.hoverIntensity || 0;
            if (intensity > 0) {
                depth = depth * (0.8 + intensity * 0.4);
                strength = strength * (0.9 + intensity * 0.2);
                blur = blur * (0.8 + intensity * 0.4);
                brightness = 1.1 + intensity * 0.1;
                saturate = 1.5 + intensity * 0.3;
            }
            
            // Efecto de click sobrescribe hover
            if (element._glassState.isPressed) {
                depth = element._glassState.originalDepth / 0.7;
            }
            
            // Crear nueva configuración
            const newConfig = { ...config, depth, strength, blur };
            
            // Ajustar dimensiones para auto-size
            if (config.autoSize) {
                const rect = element.getBoundingClientRect();
                newConfig.width = Math.max(Math.ceil(rect.width), 50);
                newConfig.height = Math.max(Math.ceil(rect.height), 30);
            }
            
            // Aplicar filtro SVG
            const filterUrl = this.getDisplacementFilter(newConfig);
            element.style.backdropFilter = `blur(${blur / 2}px) url('${filterUrl}') blur(${blur}px) brightness(${brightness}) saturate(${saturate})`;
        };
        
        // Exponer función de actualización
        element._updateGlassFilter = updateFilterState;
        
        // Eventos de click
        element.addEventListener('mousedown', () => {
            element._glassState.isPressed = true;
            element.style.transform = 'scale(0.98)';
            updateFilterState();
        });
        
        const handleMouseUp = () => {
            if (element._glassState.isPressed) {
                element._glassState.isPressed = false;
                
                // Restaurar transform basado en hover state
                const intensity = element._glassState.hoverIntensity || 0;
                const translateY = -2 * intensity;
                const scale = 1 + 0.02 * intensity;
                element.style.transform = `translateY(${translateY}px) scale(${scale})`;
                
                updateFilterState();
            }
        };
        
        element.addEventListener('mouseup', handleMouseUp);
        element.addEventListener('mouseleave', handleMouseUp);
        document.addEventListener('mouseup', handleMouseUp);
    }

    /**
     * Añade efectos de hover avanzados (coordinado con interactividad)
     */
    addHoverEffects(element, config) {
        let hoverTimeout = null;
        let currentAnimation = null;
        const duration = 300; // Duración unificada para ambas transiciones
        
        // Función de easing unificada (ease-out cuadrático)
        const easeOut = (t) => 1 - Math.pow(1 - t, 2);
        
        element.addEventListener('mouseenter', () => {
            if (element._glassState.isHovering) return;
            element._glassState.isHovering = true;
            
            // Limpiar timeout y animación previa
            if (hoverTimeout) clearTimeout(hoverTimeout);
            if (currentAnimation) cancelAnimationFrame(currentAnimation);
            
            // Eliminar TODA transición CSS para control completo de JavaScript
            element.style.transition = 'none';
            
            // Aplicar efecto de hover con transición suave
            const startTime = performance.now();
            const startIntensity = element._glassState.hoverIntensity || 0;
            
            const animate = (currentTime) => {
                if (!element._glassState.isHovering) return; // Cancelar si ya no está hovering
                
                const elapsed = currentTime - startTime;
                const t = Math.min(elapsed / duration, 1);
                
                // Aplicar la misma curva de easing
                const easedProgress = easeOut(t);
                element._glassState.hoverIntensity = startIntensity + (1 - startIntensity) * easedProgress;
                
                // Animar también el transform para consistencia
                const currentIntensity = element._glassState.hoverIntensity;
                element.style.transform = `translateY(${-2 * currentIntensity}px) scale(${1 + 0.02 * currentIntensity})`;
                
                // Usar la función coordinada de actualización
                if (element._updateGlassFilter) {
                    element._updateGlassFilter();
                }
                
                if (t < 1) {
                    currentAnimation = requestAnimationFrame(animate);
                } else {
                    currentAnimation = null;
                    element._glassState.hoverIntensity = 1;
                    // Asegurar transform final correcto
                    element.style.transform = 'translateY(-2px) scale(1.02)';
                    // Restaurar transición CSS al terminar
                    element.style.transition = 'transform 0.1s ease';
                }
            };
            currentAnimation = requestAnimationFrame(animate);
        });
        
        element.addEventListener('mouseleave', () => {
            if (!element._glassState.isHovering) return;
            element._glassState.isHovering = false;
            
            // Limpiar timeout y animación previa
            if (hoverTimeout) clearTimeout(hoverTimeout);
            if (currentAnimation) cancelAnimationFrame(currentAnimation);
            
            // Eliminar TODA transición CSS para evitar conflictos
            element.style.transition = 'none';
            
            // Transición suave de vuelta al estado original con la misma curva invertida
            const startTime = performance.now();
            const startIntensity = element._glassState.hoverIntensity || 1;
            
            const animateOut = (currentTime) => {
                if (element._glassState.isHovering) return; // Cancelar si volvió a hacer hover
                
                const elapsed = currentTime - startTime;
                const t = Math.min(elapsed / duration, 1);
                
                // Aplicar la misma curva de easing pero invertida
                const easedProgress = easeOut(t);
                element._glassState.hoverIntensity = startIntensity * (1 - easedProgress);
                
                // Animar solo el transform para mejor rendimiento
                const currentIntensity = element._glassState.hoverIntensity;
                element.style.transform = `translateY(${-2 * currentIntensity}px) scale(${1 + 0.02 * currentIntensity})`;
                
                // Solo actualizar el filtro SVG en frames específicos o al final para mejor rendimiento
                const shouldUpdateFilter = t >= 1 || elapsed % 32 < 16; // Actualizar cada ~2 frames
                if (shouldUpdateFilter && element._updateGlassFilter) {
                    element._updateGlassFilter();
                }
                
                if (t < 1) {
                    currentAnimation = requestAnimationFrame(animateOut);
                } else {
                    currentAnimation = null;
                    element._glassState.hoverIntensity = 0;
                    // Aplicar el filtro final inmediatamente
                    if (!element._glassState.isHovering && element._updateGlassFilter) {
                        element._updateGlassFilter();
                    }
                    // Asegurar transform final correcto (estado normal)
                    element.style.transform = 'translateY(0px) scale(1)';
                    // Restaurar transición CSS básica
                    element.style.transition = 'transform 0.1s ease';
                }
            };
            currentAnimation = requestAnimationFrame(animateOut);
        });
    }

    /**
     * API pública para crear un elemento con efecto de cristal
     */
    createGlassElement(options = {}) {
        const element = document.createElement('div');
        element.className = `glass glass-advanced ${options.className || ''}`;
        
        // Configurar atributos data-*
        if (options.width) element.dataset.glassWidth = options.width;
        if (options.height) element.dataset.glassHeight = options.height;
        if (options.radius) element.dataset.glassRadius = options.radius;
        if (options.depth) element.dataset.glassDepth = options.depth;
        if (options.blur) element.dataset.glassBlur = options.blur;
        if (options.strength) element.dataset.glassStrength = options.strength;
        if (options.chromaticAberration) element.dataset.glassChromaticAberration = options.chromaticAberration;
        if (options.backgroundColor) element.dataset.glassBackgroundColor = options.backgroundColor;
        if (options.autoSize) element.setAttribute('data-glass-auto-size', '');
        if (options.hoverEffects === true) element.dataset.glassHoverEffects = 'true';
        
        // Añadir contenido
        if (options.content) {
            if (typeof options.content === 'string') {
                element.innerHTML = `<div class="glass-container">${options.content}</div>`;
            } else {
                const container = document.createElement('div');
                container.className = 'glass-container';
                container.appendChild(options.content);
                element.appendChild(container);
            }
        }
        
        return element;
    }

    /**
     * API pública para actualizar un elemento existente
     */
    updateElement(element, newConfig) {
        Object.keys(newConfig).forEach(key => {
            const dataKey = key === 'backgroundColor' ? 'glassBackgroundColor' : 
                           key === 'chromaticAberration' ? 'glassChromaticAberration' :
                           `glass${key.charAt(0).toUpperCase() + key.slice(1)}`;
            element.dataset[dataKey] = newConfig[key];
        });
        
        // Forzar re-renderizado
        element.removeAttribute('data-glass-enhanced');
        this.enhanceElement(element);
    }
}

// Inicializar automáticamente cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.glassEffects = new GlassEffects();
    });
} else {
    window.glassEffects = new GlassEffects();
}

// Exportar para uso manual
window.GlassEffects = GlassEffects;