const initSketchfab = (iframeEl, uid) => {

    return new Promise((resolve, reject) => {

        // Data
        let data = {};
        const setData = (key, value) => {

            data[key] = value;

            if(data.api && data.materials && data.nodes) {

                resolve(data); // すべてのデータが揃ったらresolve

            }

        };

        // Sketchfab
        const version = '1.12.1';
        const client = new window.Sketchfab(version, iframeEl);
        client.init(uid, {
            ui_infos: 0,
            ui_help: 0,
            ui_settings: 0,
            ui_vr: 0,
            ui_ar: 0,
            ui_inspector: 0,
            ui_fullscreen: 0,
            ui_watermark: 0,
            ui_stop: 0,
            success(api) {

                api.start();
                api.addEventListener('viewerready', () => {

                    setData('api', api);

                    api.getMaterialList((err, materials) => { // マテリアルの取得

                        if (! err) setData('materials', materials);

                    });
                    api.getNodeMap((err, nodes) => { // Nodeの取得：ロゴの表示/非表示に必要

                        if (! err) setData('nodes', nodes);

                    });

                });

            },
            error(err) {

                reject(err);

            }
        });

    });

};
const getTargetMaterials = (allMaterials, allMaterialCombinationGroups, targetGroupKey) => {

    const targetGroupKeys = [targetGroupKey];

    const targetMaterialCombinationGroups = allMaterialCombinationGroups.filter((item) => {

        for(const index in targetGroupKeys) {

            const targetGroupKey = targetGroupKeys[index];

            if(item.key === targetGroupKey) {

                return true;

            }

        }

        return false;

    });
    const targetMaterials = allMaterials.filter((item) => {

        for(const index in targetGroupKeys) {

            const targetGroupKey = targetGroupKeys[index];

            if(item.name === targetGroupKey) {

                return true;

            }

        }

        return false;

    });

    if(targetMaterialCombinationGroups.length > 0) {

        const componentGroupKeys = targetMaterialCombinationGroups.map((group) => {

            return group.component_group_keys;

        });
        const flattenedComponentGroupKeys = _.flatten(componentGroupKeys);

        return allMaterials.filter((material) => {

            return flattenedComponentGroupKeys.includes(material.name);

        });

    } else if(targetMaterials.length > 0) {

        return targetMaterials;

    } else {

        console.error('指定されたグループが見つかりませんでした: ' + targetGroupKey);

    }

    return [];

};

/*
    Original source: https://jsfiddle.net/PadreZippo/kuv70apq/
 */
const GAMMA = 2.4;
const srgbToLinear1 = (c) => {
    var v = 0.0;
    if (c < 0.04045) {
        if (c >= 0.0) v = c * (1.0 / 12.92);
    } else {
        v = Math.pow((c + 0.055) * (1.0 / 1.055), GAMMA);
    }
    return v;
};
const srgbToLinear = (c, out) => {
    var col = out || new Array(c.length);

    if (c.length > 2 && c.length < 5) {
        col[0] = srgbToLinear1(c[0]);
        col[1] = srgbToLinear1(c[1]);
        col[2] = srgbToLinear1(c[2]);
        if (col.length > 3 && c.length > 3) col[3] = c[3];
    } else {
        throw new Error('Invalid color. Expected 3 or 4 components, but got ' + c.length);
    }
    return col;
};
const hexToRgb = (hexColor) => {
    var m = hexColor.match(/^#([0-9a-f]{6})$/i);
    if (m) {
        return [
            parseInt(m[1].substr(0, 2), 16) / 255,
            parseInt(m[1].substr(2, 2), 16) / 255,
            parseInt(m[1].substr(4, 2), 16) / 255
        ];
    } else {
        throw new Error('Invalid color: ' + hexColor);
    }
};
const getSketchfabColor = (hexColor) => {

    return srgbToLinear(
        hexToRgb(hexColor)
    );

}

export {
    initSketchfab,
    getSketchfabColor,
    getTargetMaterials,
}
