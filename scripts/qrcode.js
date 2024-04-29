document
    .getElementById('qr1')
    .animateQRCode((targets, _x, _y, _count, entity) => ({
        targets,
        from: entity === 'module' ? Math.random() * 200 : 200,
        duration: 1000,
        easing: 'cubic-bezier(.5,0,1,1)',
        web: { opacity: [0, 1], scale: [0.5, 1.1, 1] },
    }));