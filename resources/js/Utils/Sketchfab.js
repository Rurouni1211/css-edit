const getSketchfabClient = (iframe, version = '1.12.1') => {

  return new Sketchfab(version, iframe);

};

export {
    getSketchfabClient,
};
