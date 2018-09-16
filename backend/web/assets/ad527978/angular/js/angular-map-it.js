var angularMaps = angular.module('angular-map-it', []);



(function(){d3.geo = {};

var d3_geo_radians = Math.PI / 180;
// TODO clip input coordinates on opposite hemisphere
d3.geo.azimuthal = function() {
  var mode = "orthographic", // or stereographic, gnomonic, equidistant or equalarea
      origin,
      scale = 200,
      translate = [480, 250],
      x0,
      y0,
      cy0,
      sy0;

  function azimuthal(coordinates) {
    var x1 = coordinates[0] * d3_geo_radians - x0,
        y1 = coordinates[1] * d3_geo_radians,
        cx1 = Math.cos(x1),
        sx1 = Math.sin(x1),
        cy1 = Math.cos(y1),
        sy1 = Math.sin(y1),
        cc = mode !== "orthographic" ? sy0 * sy1 + cy0 * cy1 * cx1 : null,
        c,
        k = mode === "stereographic" ? 1 / (1 + cc)
          : mode === "gnomonic" ? 1 / cc
          : mode === "equidistant" ? (c = Math.acos(cc), c ? c / Math.sin(c) : 0)
          : mode === "equalarea" ? Math.sqrt(2 / (1 + cc))
          : 1,
        x = k * cy1 * sx1,
        y = k * (sy0 * cy1 * cx1 - cy0 * sy1);
    return [
      scale * x + translate[0],
      scale * y + translate[1]
    ];
  }

  azimuthal.invert = function(coordinates) {
    var x = (coordinates[0] - translate[0]) / scale,
        y = (coordinates[1] - translate[1]) / scale,
        p = Math.sqrt(x * x + y * y),
        c = mode === "stereographic" ? 2 * Math.atan(p)
          : mode === "gnomonic" ? Math.atan(p)
          : mode === "equidistant" ? p
          : mode === "equalarea" ? 2 * Math.asin(.5 * p)
          : Math.asin(p),
        sc = Math.sin(c),
        cc = Math.cos(c);
    return [
      (x0 + Math.atan2(x * sc, p * cy0 * cc + y * sy0 * sc)) / d3_geo_radians,
      Math.asin(cc * sy0 - (p ? (y * sc * cy0) / p : 0)) / d3_geo_radians
    ];
  };

  azimuthal.mode = function(x) {
    if (!arguments.length) return mode;
    mode = x + "";
    return azimuthal;
  };

  azimuthal.origin = function(x) {
    if (!arguments.length) return origin;
    origin = x;
    x0 = origin[0] * d3_geo_radians;
    y0 = origin[1] * d3_geo_radians;
    cy0 = Math.cos(y0);
    sy0 = Math.sin(y0);
    return azimuthal;
  };

  azimuthal.scale = function(x) {
    if (!arguments.length) return scale;
    scale = +x;
    return azimuthal;
  };

  azimuthal.translate = function(x) {
    if (!arguments.length) return translate;
    translate = [+x[0], +x[1]];
    return azimuthal;
  };

  return azimuthal.origin([0, 0]);
};
// Derived from Tom Carden's Albers implementation for Protovis.
// http://gist.github.com/476238
// http://mathworld.wolfram.com/AlbersEqual-AreaConicProjection.html

d3.geo.albers = function() {
  var origin = [-98, 38],
      parallels = [29.5, 45.5],
      scale = 1000,
      translate = [480, 250],
      lng0, // d3_geo_radians * origin[0]
      n,
      C,
      p0;

  function albers(coordinates) {
    var t = n * (d3_geo_radians * coordinates[0] - lng0),
        p = Math.sqrt(C - 2 * n * Math.sin(d3_geo_radians * coordinates[1])) / n;
    return [
      scale * p * Math.sin(t) + translate[0],
      scale * (p * Math.cos(t) - p0) + translate[1]
    ];
  }

  albers.invert = function(coordinates) {
    var x = (coordinates[0] - translate[0]) / scale,
        y = (coordinates[1] - translate[1]) / scale,
        p0y = p0 + y,
        t = Math.atan2(x, p0y),
        p = Math.sqrt(x * x + p0y * p0y);
    return [
      (lng0 + t / n) / d3_geo_radians,
      Math.asin((C - p * p * n * n) / (2 * n)) / d3_geo_radians
    ];
  };

  function reload() {
    var phi1 = d3_geo_radians * parallels[0],
        phi2 = d3_geo_radians * parallels[1],
        lat0 = d3_geo_radians * origin[1],
        s = Math.sin(phi1),
        c = Math.cos(phi1);
    lng0 = d3_geo_radians * origin[0];
    n = .5 * (s + Math.sin(phi2));
    C = c * c + 2 * n * s;
    p0 = Math.sqrt(C - 2 * n * Math.sin(lat0)) / n;
    return albers;
  }

  albers.origin = function(x) {
    if (!arguments.length) return origin;
    origin = [+x[0], +x[1]];
    return reload();
  };

  albers.parallels = function(x) {
    if (!arguments.length) return parallels;
    parallels = [+x[0], +x[1]];
    return reload();
  };

  albers.scale = function(x) {
    if (!arguments.length) return scale;
    scale = +x;
    return albers;
  };

  albers.translate = function(x) {
    if (!arguments.length) return translate;
    translate = [+x[0], +x[1]];
    return albers;
  };

  return reload();
};

// A composite projection for the United States, 960x500. The set of standard
// parallels for each region comes from USGS, which is published here:
// http://egsc.usgs.gov/isb/pubs/MapProjections/projections.html#albers
// TODO allow the composite projection to be rescaled?
d3.geo.albersUsa = function() {
  var lower48 = d3.geo.albers();

  var alaska = d3.geo.albers()
      .origin([-160, 60])
      .parallels([55, 65]);

  var hawaii = d3.geo.albers()
      .origin([-160, 20])
      .parallels([8, 18]);

  var puertoRico = d3.geo.albers()
      .origin([-60, 10])
      .parallels([8, 18]);

  function albersUsa(coordinates) {
    var lon = coordinates[0],
        lat = coordinates[1];
    return (lat > 50 ? alaska
        : lon < -140 ? hawaii
        : lat < 21 ? puertoRico
        : lower48)(coordinates);
  }

  albersUsa.scale = function(x) {
    if (!arguments.length) return lower48.scale();
    lower48.scale(x);
    alaska.scale(x * .6);
    hawaii.scale(x);
    puertoRico.scale(x * 1.5);
    return albersUsa.translate(lower48.translate());
  };

  albersUsa.translate = function(x) {
    if (!arguments.length) return lower48.translate();
    var dz = lower48.scale() / 1000,
        dx = x[0],
        dy = x[1];
    lower48.translate(x);
    alaska.translate([dx - 400 * dz, dy + 170 * dz]);
    hawaii.translate([dx - 190 * dz, dy + 200 * dz]);
    puertoRico.translate([dx + 580 * dz, dy + 430 * dz]);
    return albersUsa;
  };

  return albersUsa.scale(lower48.scale());
};
d3.geo.bonne = function() {
  var scale = 200,
      translate = [480, 250],
      x0, // origin longitude in radians
      y0, // origin latitude in radians
      y1, // parallel latitude in radians
      c1; // cot(y1)

  function bonne(coordinates) {
    var x = coordinates[0] * d3_geo_radians - x0,
        y = coordinates[1] * d3_geo_radians - y0;
    if (y1) {
      var p = c1 + y1 - y, E = x * Math.cos(y) / p;
      x = p * Math.sin(E);
      y = p * Math.cos(E) - c1;
    } else {
      x *= Math.cos(y);
      y *= -1;
    }
    return [
      scale * x + translate[0],
      scale * y + translate[1]
    ];
  }

  bonne.invert = function(coordinates) {
    var x = (coordinates[0] - translate[0]) / scale,
        y = (coordinates[1] - translate[1]) / scale;
    if (y1) {
      var c = c1 + y, p = Math.sqrt(x * x + c * c);
      y = c1 + y1 - p;
      x = x0 + p * Math.atan2(x, c) / Math.cos(y);
    } else {
      y *= -1;
      x /= Math.cos(y);
    }
    return [
      x / d3_geo_radians,
      y / d3_geo_radians
    ];
  };

  // 90° for Werner, 0° for Sinusoidal
  bonne.parallel = function(x) {
    if (!arguments.length) return y1 / d3_geo_radians;
    c1 = 1 / Math.tan(y1 = x * d3_geo_radians);
    return bonne;
  };

  bonne.origin = function(x) {
    if (!arguments.length) return [x0 / d3_geo_radians, y0 / d3_geo_radians];
    x0 = x[0] * d3_geo_radians;
    y0 = x[1] * d3_geo_radians;
    return bonne;
  };

  bonne.scale = function(x) {
    if (!arguments.length) return scale;
    scale = +x;
    return bonne;
  };

  bonne.translate = function(x) {
    if (!arguments.length) return translate;
    translate = [+x[0], +x[1]];
    return bonne;
  };

  return bonne.origin([0, 0]).parallel(45);
};
d3.geo.equirectangular = function() {
  var scale = 500,
      translate = [480, 250];

  function equirectangular(coordinates) {
    var x = coordinates[0] / 360,
        y = -coordinates[1] / 360;
    return [
      scale * x + translate[0],
      scale * y + translate[1]
    ];
  }

  equirectangular.invert = function(coordinates) {
    var x = (coordinates[0] - translate[0]) / scale,
        y = (coordinates[1] - translate[1]) / scale;
    return [
      360 * x,
      -360 * y
    ];
  };

  equirectangular.scale = function(x) {
    if (!arguments.length) return scale;
    scale = +x;
    return equirectangular;
  };

  equirectangular.translate = function(x) {
    if (!arguments.length) return translate;
    translate = [+x[0], +x[1]];
    return equirectangular;
  };

  return equirectangular;
};
d3.geo.mercator = function() {
  var scale = 500,
      translate = [480, 250];

  function mercator(coordinates) {
    var x = coordinates[0] / 360,
        y = -(Math.log(Math.tan(Math.PI / 4 + coordinates[1] * d3_geo_radians / 2)) / d3_geo_radians) / 360;
    return [
      scale * x + translate[0],
      scale * Math.max(-.5, Math.min(.5, y)) + translate[1]
    ];
  }

  mercator.invert = function(coordinates) {
    var x = (coordinates[0] - translate[0]) / scale,
        y = (coordinates[1] - translate[1]) / scale;
    return [
      360 * x,
      2 * Math.atan(Math.exp(-360 * y * d3_geo_radians)) / d3_geo_radians - 90
    ];
  };

  mercator.scale = function(x) {
    if (!arguments.length) return scale;
    scale = +x;
    return mercator;
  };

  mercator.translate = function(x) {
    if (!arguments.length) return translate;
    translate = [+x[0], +x[1]];
    return mercator;
  };

  return mercator;
};
function d3_geo_type(types, defaultValue) {
  return function(object) {
    return object && object.type in types ? types[object.type](object) : defaultValue;
  };
}
/**
 * Returns a function that, given a GeoJSON object (e.g., a feature), returns
 * the corresponding SVG path. The function can be customized by overriding the
 * projection. Point features are mapped to circles with a default radius of
 * 4.5px; the radius can be specified either as a constant or a function that
 * is evaluated per object.
 */
d3.geo.path = function() {
  var pointRadius = 4.5,
      pointCircle = d3_path_circle(pointRadius),
      projection = d3.geo.albersUsa();

  function path(d, i) {
    if (typeof pointRadius === "function") {
      pointCircle = d3_path_circle(pointRadius.apply(this, arguments));
    }
    return pathType(d) || null;
  }

  function project(coordinates) {
    return projection(coordinates).join(",");
  }

  var pathType = d3_geo_type({

    FeatureCollection: function(o) {
      var path = [],
          features = o.features,
          i = -1, // features.index
          n = features.length;
      while (++i < n) path.push(pathType(features[i].geometry));
      return path.join("");
    },

    Feature: function(o) {
      return pathType(o.geometry);
    },

    Point: function(o) {
      return "M" + project(o.coordinates) + pointCircle;
    },

    MultiPoint: function(o) {
      var path = [],
          coordinates = o.coordinates,
          i = -1, // coordinates.index
          n = coordinates.length;
      while (++i < n) path.push("M", project(coordinates[i]), pointCircle);
      return path.join("");
    },

    LineString: function(o) {
      var path = ["M"],
          coordinates = o.coordinates,
          i = -1, // coordinates.index
          n = coordinates.length;
      while (++i < n) path.push(project(coordinates[i]), "L");
      path.pop();
      return path.join("");
    },

    MultiLineString: function(o) {
      var path = [],
          coordinates = o.coordinates,
          i = -1, // coordinates.index
          n = coordinates.length,
          subcoordinates, // coordinates[i]
          j, // subcoordinates.index
          m; // subcoordinates.length
      while (++i < n) {
        subcoordinates = coordinates[i];
        j = -1;
        m = subcoordinates.length;
        path.push("M");
        while (++j < m) path.push(project(subcoordinates[j]), "L");
        path.pop();
      }
      return path.join("");
    },

    Polygon: function(o) {
      var path = [],
          coordinates = o.coordinates,
          i = -1, // coordinates.index
          n = coordinates.length,
          subcoordinates, // coordinates[i]
          j, // subcoordinates.index
          m; // subcoordinates.length
      while (++i < n) {
        subcoordinates = coordinates[i];
        j = -1;
        if ((m = subcoordinates.length - 1) > 0) {
          path.push("M");
          while (++j < m) path.push(project(subcoordinates[j]), "L");
          path[path.length - 1] = "Z";
        }
      }
      return path.join("");
    },

    MultiPolygon: function(o) {
      var path = [],
          coordinates = o.coordinates,
          i = -1, // coordinates index
          n = coordinates.length,
          subcoordinates, // coordinates[i]
          j, // subcoordinates index
          m, // subcoordinates.length
          subsubcoordinates, // subcoordinates[j]
          k, // subsubcoordinates index
          p; // subsubcoordinates.length
      while (++i < n) {
        subcoordinates = coordinates[i];
        j = -1;
        m = subcoordinates.length;
        while (++j < m) {
          subsubcoordinates = subcoordinates[j];
          k = -1;
          if ((p = subsubcoordinates.length - 1) > 0) {
            path.push("M");
            while (++k < p) path.push(project(subsubcoordinates[k]), "L");
            path[path.length - 1] = "Z";
          }
        }
      }
      return path.join("");
    },

    GeometryCollection: function(o) {
      var path = [],
          geometries = o.geometries,
          i = -1, // geometries index
          n = geometries.length;
      while (++i < n) path.push(pathType(geometries[i]));
      return path.join("");
    }

  });

  var areaType = path.area = d3_geo_type({

    FeatureCollection: function(o) {
      var area = 0,
          features = o.features,
          i = -1, // features.index
          n = features.length;
      while (++i < n) area += areaType(features[i]);
      return area;
    },

    Feature: function(o) {
      return areaType(o.geometry);
    },

    Polygon: function(o) {
      return polygonArea(o.coordinates);
    },

    MultiPolygon: function(o) {
      var sum = 0,
          coordinates = o.coordinates,
          i = -1, // coordinates index
          n = coordinates.length;
      while (++i < n) sum += polygonArea(coordinates[i]);
      return sum;
    },

    GeometryCollection: function(o) {
      var sum = 0,
          geometries = o.geometries,
          i = -1, // geometries index
          n = geometries.length;
      while (++i < n) sum += areaType(geometries[i]);
      return sum;
    }

  }, 0);

  function polygonArea(coordinates) {
    var sum = area(coordinates[0]), // exterior ring
        i = 0, // coordinates.index
        n = coordinates.length;
    while (++i < n) sum -= area(coordinates[i]); // holes
    return sum;
  }

  function polygonCentroid(coordinates) {
    var polygon = d3.geom.polygon(coordinates[0].map(projection)), // exterior ring
        area = polygon.area(),
        centroid = polygon.centroid(area < 0 ? (area *= -1, 1) : -1),
        x = centroid[0],
        y = centroid[1],
        z = area,
        i = 0, // coordinates index
        n = coordinates.length;
    while (++i < n) {
      polygon = d3.geom.polygon(coordinates[i].map(projection)); // holes
      area = polygon.area();
      centroid = polygon.centroid(area < 0 ? (area *= -1, 1) : -1);
      x -= centroid[0];
      y -= centroid[1];
      z -= area;
    }
    return [x, y, 6 * z]; // weighted centroid
  }

  var centroidType = path.centroid = d3_geo_type({

    // TODO FeatureCollection
    // TODO Point
    // TODO MultiPoint
    // TODO LineString
    // TODO MultiLineString
    // TODO GeometryCollection

    Feature: function(o) {
      return centroidType(o.geometry);
    },

    Polygon: function(o) {
      var centroid = polygonCentroid(o.coordinates);
      return [centroid[0] / centroid[2], centroid[1] / centroid[2]];
    },

    MultiPolygon: function(o) {
      var area = 0,
          coordinates = o.coordinates,
          centroid,
          x = 0,
          y = 0,
          z = 0,
          i = -1, // coordinates index
          n = coordinates.length;
      while (++i < n) {
        centroid = polygonCentroid(coordinates[i]);
        x += centroid[0];
        y += centroid[1];
        z += centroid[2];
      }
      return [x / z, y / z];
    }

  });

  function area(coordinates) {
    return Math.abs(d3.geom.polygon(coordinates.map(projection)).area());
  }

  path.projection = function(x) {
    projection = x;
    return path;
  };

  path.pointRadius = function(x) {
    if (typeof x === "function") pointRadius = x;
    else {
      pointRadius = +x;
      pointCircle = d3_path_circle(pointRadius);
    }
    return path;
  };

  return path;
};

function d3_path_circle(radius) {
  return "m0," + radius
      + "a" + radius + "," + radius + " 0 1,1 0," + (-2 * radius)
      + "a" + radius + "," + radius + " 0 1,1 0," + (+2 * radius)
      + "z";
}
/**
 * Given a GeoJSON object, returns the corresponding bounding box. The bounding
 * box is represented by a two-dimensional array: [[left, bottom], [right,
 * top]], where left is the minimum longitude, bottom is the minimum latitude,
 * right is maximum longitude, and top is the maximum latitude.
 */
d3.geo.bounds = function(feature) {
  var left = Infinity,
      bottom = Infinity,
      right = -Infinity,
      top = -Infinity;
  d3_geo_bounds(feature, function(x, y) {
    if (x < left) left = x;
    if (x > right) right = x;
    if (y < bottom) bottom = y;
    if (y > top) top = y;
  });
  return [[left, bottom], [right, top]];
};

function d3_geo_bounds(o, f) {
  if (o.type in d3_geo_boundsTypes) d3_geo_boundsTypes[o.type](o, f);
}

var d3_geo_boundsTypes = {
  Feature: d3_geo_boundsFeature,
  FeatureCollection: d3_geo_boundsFeatureCollection,
  LineString: d3_geo_boundsLineString,
  MultiLineString: d3_geo_boundsMultiLineString,
  MultiPoint: d3_geo_boundsLineString,
  MultiPolygon: d3_geo_boundsMultiPolygon,
  Point: d3_geo_boundsPoint,
  Polygon: d3_geo_boundsPolygon
};

function d3_geo_boundsFeature(o, f) {
  d3_geo_bounds(o.geometry, f);
}

function d3_geo_boundsFeatureCollection(o, f) {
  for (var a = o.features, i = 0, n = a.length; i < n; i++) {
    d3_geo_bounds(a[i].geometry, f);
  }
}

function d3_geo_boundsLineString(o, f) {
  for (var a = o.coordinates, i = 0, n = a.length; i < n; i++) {
    f.apply(null, a[i]);
  }
}

function d3_geo_boundsMultiLineString(o, f) {
  for (var a = o.coordinates, i = 0, n = a.length; i < n; i++) {
    for (var b = a[i], j = 0, m = b.length; j < m; j++) {
      f.apply(null, b[j]);
    }
  }
}

function d3_geo_boundsMultiPolygon(o, f) {
  for (var a = o.coordinates, i = 0, n = a.length; i < n; i++) {
    for (var b = a[i][0], j = 0, m = b.length; j < m; j++) {
      f.apply(null, b[j]);
    }
  }
}

function d3_geo_boundsPoint(o, f) {
  f.apply(null, o.coordinates);
}

function d3_geo_boundsPolygon(o, f) {
  for (var a = o.coordinates[0], i = 0, n = a.length; i < n; i++) {
    f.apply(null, a[i]);
  }
}
// TODO breakAtDateLine?

d3.geo.circle = function() {
  var origin = [0, 0],
      degrees = 90 - 1e-2,
      radians = degrees * d3_geo_radians,
      arc = d3.geo.greatArc().target(Object);

  function circle() {
    // TODO render a circle as a Polygon
  }

  function visible(point) {
    return arc.distance(point) < radians;
  }

  circle.clip = function(d) {
    arc.source(typeof origin === "function" ? origin.apply(this, arguments) : origin);
    return clipType(d);
  };

  var clipType = d3_geo_type({

    FeatureCollection: function(o) {
      var features = o.features.map(clipType).filter(Object);
      return features && (o = Object.create(o), o.features = features, o);
    },

    Feature: function(o) {
      var geometry = clipType(o.geometry);
      return geometry && (o = Object.create(o), o.geometry = geometry, o);
    },

    Point: function(o) {
      return visible(o.coordinates) && o;
    },

    MultiPoint: function(o) {
      var coordinates = o.coordinates.filter(visible);
      return coordinates.length && {
        type: o.type,
        coordinates: coordinates
      };
    },

    LineString: function(o) {
      var coordinates = clip(o.coordinates);
      return coordinates.length && (o = Object.create(o), o.coordinates = coordinates, o);
    },

    MultiLineString: function(o) {
      var coordinates = o.coordinates.map(clip).filter(function(d) { return d.length; });
      return coordinates.length && (o = Object.create(o), o.coordinates = coordinates, o);
    },

    Polygon: function(o) {
      var coordinates = o.coordinates.map(clip);
      return coordinates[0].length && (o = Object.create(o), o.coordinates = coordinates, o);
    },

    MultiPolygon: function(o) {
      var coordinates = o.coordinates.map(function(d) { return d.map(clip); }).filter(function(d) { return d[0].length; });
      return coordinates.length && (o = Object.create(o), o.coordinates = coordinates, o);
    },

    GeometryCollection: function(o) {
      var geometries = o.geometries.map(clipType).filter(Object);
      return geometries.length && (o = Object.create(o), o.geometries = geometries, o);
    }

  });

  function clip(coordinates) {
    var i = -1,
        n = coordinates.length,
        clipped = [],
        p0,
        p1,
        p2,
        d0,
        d1;

    while (++i < n) {
      d1 = arc.distance(p2 = coordinates[i]);
      if (d1 < radians) {
        if (p1) clipped.push(d3_geo_greatArcInterpolate(p1, p2)((d0 - radians) / (d0 - d1)));
        clipped.push(p2);
        p0 = p1 = null;
      } else {
        p1 = p2;
        if (!p0 && clipped.length) {
          clipped.push(d3_geo_greatArcInterpolate(clipped[clipped.length - 1], p1)((radians - d0) / (d1 - d0)));
          p0 = p1;
        }
      }
      d0 = d1;
    }

    if (p1 && clipped.length) {
      d1 = arc.distance(p2 = clipped[0]);
      clipped.push(d3_geo_greatArcInterpolate(p1, p2)((d0 - radians) / (d0 - d1)));
    }

    return resample(clipped);
  }

  // Resample coordinates, creating great arcs between each.
  function resample(coordinates) {
    var i = 0,
        n = coordinates.length,
        j,
        m,
        resampled = n ? [coordinates[0]] : coordinates,
        resamples,
        origin = arc.source();

    while (++i < n) {
      resamples = arc.source(coordinates[i - 1])(coordinates[i]).coordinates;
      for (j = 0, m = resamples.length; ++j < m;) resampled.push(resamples[j]);
    }

    arc.source(origin);
    return resampled;
  }

  circle.origin = function(x) {
    if (!arguments.length) return origin;
    origin = x;
    return circle;
  };

  circle.angle = function(x) {
    if (!arguments.length) return degrees;
    radians = (degrees = +x) * d3_geo_radians;
    return circle;
  };

  // Precision is specified in degrees.
  circle.precision = function(x) {
    if (!arguments.length) return arc.precision();
    arc.precision(x);
    return circle;
  };

  return circle;
}
d3.geo.greatArc = function() {
  var source = d3_geo_greatArcSource,
      target = d3_geo_greatArcTarget,
      precision = 6 * d3_geo_radians;

  function greatArc() {
    var a = typeof source === "function" ? source.apply(this, arguments) : source,
        b = typeof target === "function" ? target.apply(this, arguments) : target,
        i = d3_geo_greatArcInterpolate(a, b),
        dt = precision / i.d,
        t = 0,
        coordinates = [a];
    while ((t += dt) < 1) coordinates.push(i(t));
    coordinates.push(b);
    return {
      type: "LineString",
      coordinates: coordinates
    };
  }

  // Length returned in radians; multiply by radius for distance.
  greatArc.distance = function() {
    var a = typeof source === "function" ? source.apply(this, arguments) : source,
        b = typeof target === "function" ? target.apply(this, arguments) : target;
     return d3_geo_greatArcInterpolate(a, b).d;
  };

  greatArc.source = function(x) {
    if (!arguments.length) return source;
    source = x;
    return greatArc;
  };

  greatArc.target = function(x) {
    if (!arguments.length) return target;
    target = x;
    return greatArc;
  };

  // Precision is specified in degrees.
  greatArc.precision = function(x) {
    if (!arguments.length) return precision / d3_geo_radians;
    precision = x * d3_geo_radians;
    return greatArc;
  };

  return greatArc;
};

function d3_geo_greatArcSource(d) {
  return d.source;
}

function d3_geo_greatArcTarget(d) {
  return d.target;
}

function d3_geo_greatArcInterpolate(a, b) {
  var x0 = a[0] * d3_geo_radians, cx0 = Math.cos(x0), sx0 = Math.sin(x0),
      y0 = a[1] * d3_geo_radians, cy0 = Math.cos(y0), sy0 = Math.sin(y0),
      x1 = b[0] * d3_geo_radians, cx1 = Math.cos(x1), sx1 = Math.sin(x1),
      y1 = b[1] * d3_geo_radians, cy1 = Math.cos(y1), sy1 = Math.sin(y1),
      d = interpolate.d = Math.acos(Math.max(-1, Math.min(1, sy0 * sy1 + cy0 * cy1 * Math.cos(x1 - x0)))),
      sd = Math.sin(d);

  // From http://williams.best.vwh.net/avform.htm#Intermediate
  function interpolate(t) {
    var A = Math.sin(d - (t *= d)) / sd,
        B = Math.sin(t) / sd,
        x = A * cy0 * cx0 + B * cy1 * cx1,
        y = A * cy0 * sx0 + B * cy1 * sx1,
        z = A * sy0       + B * sy1;
    return [
      Math.atan2(y, x) / d3_geo_radians,
      Math.atan2(z, Math.sqrt(x * x + y * y)) / d3_geo_radians
    ];
  }

  return interpolate;
}
d3.geo.greatCircle = d3.geo.circle;
})();

angularMaps.directive('globeTouch', function ($parse) {
  var directiveDefinitionObject = {
    //We restrict its use to an element
    //as usually  <globe> is semantically
    //more understandable
    restrict: 'E',
    //this is important,
    //we don't want to overwrite our directive declaration
    //in the HTML mark-up
    replace: false,
    //our data source would be json passed thru globe-data attribute
    //globe data value range would be an array of size 2 passed thru value-range attribute
    //color range would be an array of size 2 passed thru color-range attribute
    //dimension would be number passed thru dimension attribute
    scope: {
      worldData: '=worldData',
      valueRange: '=valueRange',
      colorRange: '=colorRange',
      dimension: '=dimension',
      countryFillColor: '=countryFillColor',
      countryBorderColor: '=countryBorderColor'
    },
    link: function (scope, element, attrs) {
      var MIN_DIMENSION=200;
      var MAX_DIMENSION=800;
      if(scope.dimension==null) {
        scope.dimension = 600;
      }
      if(scope.colorRange==null) {
        scope.colorRange = ["#F03B20", "#FFEDA0"];
      }
      if(scope.valueRange==null) {
        scope.valueRange = [0, 100];
      }
      if(scope.worldData==null) {
        scope.worldData = [];
      }
      if(scope.countryFillColor==null) {
        scope.countryFillColor = "#aaa";
      }
      if(scope.countryBorderColor==null) {
        scope.countryBorderColor = "#fff";
      }


      if(scope.dimension<MIN_DIMENSION) {
        scope.dimension=MIN_DIMENSION;
      }
      var feature;
      var MIN_SCALE=scope.dimension/MIN_DIMENSION;
      var MAX_SCALE=MAX_DIMENSION/100;
      var scale = MIN_SCALE;
      var translateX = scope.dimension/2,
          translateY = scope.dimension/2;

      var projection = d3.geo.azimuthal()
          .scale(scale*100)
          .origin([16.07, 43.3])
          .mode("orthographic")
          .translate([translateX, translateY]);

      var circle = d3.geo.greatCircle()
          .origin(projection.origin());

      var path = d3.geo.path()
          .projection(projection);

      var m0,
          o0;
      var rotate = [0,0];

      var zoom = d3.behavior.zoom()
          .scaleExtent([MIN_SCALE, MAX_SCALE])
          .on("zoomstart", function(){
            m0 = [d3.event.sourceEvent.pageX, d3.event.sourceEvent.pageY];
            var proj = rotate;
            o0 = [-proj[0],-proj[1]];

            d3.event.sourceEvent.stopPropagation();
          })
          .on("zoom", function() {
            if (m0) {
              var constant = (scale < 4) ? 4 : scale * 2;

              var m1 = [d3.event.sourceEvent.pageX, d3.event.sourceEvent.pageY],
                  o1 = [o0[0] + (m0[0] - m1[0]) / constant, o0[1] + (m1[1] - m0[1]) / constant];

            }


            rotate = [-o1[0], -o1[1]];
            if(d3.event.scale >= MIN_SCALE) {
              scale = d3.event.scale;
              if(d3.event.scale >= MAX_SCALE) {
                scale = MAX_SCALE;
              }
            } else {
              scale = MIN_SCALE;
            }

            projection.scale(scale*100);
            refresh(50);
          });

      var m0,
          o0, m1, o1;

      function dragstart() {
        d3.event.sourceEvent.stopPropagation();
      }
      function ondrag() {
        var m1 = [d3.event.x, d3.event.y],
              o1 = [o0[0] + (m0[0] - m1[0]) / 4, o0[1] + (m1[1] - m0[1]) / 4];
        projection.origin(o1);
        circle.origin(o1);

        refresh();

        m0=m1;
        o0=o1;
      }
      function dragend() {
      }

      var drag = d3.behavior.drag();
              drag.on("dragstart", dragstart)
              .on("drag", ondrag)
              .on("dragend", dragend);

      var svg = d3.select(element[0])
          .append("svg:svg")
          .attr("width", scope.dimension)
          .attr("height", scope.dimension)
          .call(zoom)
          .call(drag);

      var color, data;

      color = d3.scale.linear()
        .domain(scope.valueRange)
        .range(scope.colorRange)
        .clamp(true);

      data = scope.worldData;
      data = d3.map(scope.worldData, function(d) { return d.countryCode; });



      feature = svg.selectAll("path")
          .data(worldTopoData.features)
          .enter().append("svg:path")
          .attr("d", clip)
          .attr("fill", scope.countryFillColor)
          .attr("stroke", scope.countryBorderColor);

      feature.append("svg:title")
          .text(function(d) { return d.properties.name; });

      feature.filter(function(d) { return data.has(d.id); })
          .style("fill", function(d) { var c= color(data.get(d.id).value); return c; });




      function refresh(duration) {
        (duration ? feature.transition().duration(duration) : feature).attr("d", clip);
      }

      function clip(d) {
        return path(circle.clip(d));
      }

    }
  };

  return directiveDefinitionObject;
});

angularMaps.directive('globe', function ($parse) {
  var directiveDefinitionObject = {
    //We restrict its use to an element
    //as usually  <globe> is semantically
    //more understandable
    restrict: 'E',
    //this is important,
    //we don't want to overwrite our directive declaration
    //in the HTML mark-up
    replace: false,
    //our data source would be json passed thru globe-data attribute
    //globe data value range would be an array of size 2 passed thru value-range attribute
    //color range would be an array of size 2 passed thru color-range attribute
    //dimension would be number passed thru dimension attribute
    scope: {
      worldData: '=worldData',
      valueRange: '=valueRange',
      colorRange: '=colorRange',
      dimension: '=dimension',
      countryFillColor: '=countryFillColor',
      countryBorderColor: '=countryBorderColor'
    },
    link: function (scope, element, attrs) {
      var MIN_DIMENSION=200;
      var MAX_DIMENSION=800;
      if(scope.dimension==null) {
        scope.dimension = 600;
      }
      if(scope.colorRange==null) {
        scope.colorRange = ["#F03B20", "#FFEDA0"];
      }
      if(scope.valueRange==null) {
        scope.valueRange = [0, 100];
      }
      if(scope.worldData==null) {
        scope.worldData = [];
      }
      if(scope.countryFillColor==null) {
        scope.countryFillColor = "#aaa";
      }
      if(scope.countryBorderColor==null) {
        scope.countryBorderColor = "#fff";
      }

      if(scope.dimension<MIN_DIMENSION) {
        scope.dimension=MIN_DIMENSION;
      }
      var feature;
      var MIN_SCALE=scope.dimension/MIN_DIMENSION;
      var MAX_SCALE=MAX_DIMENSION/100;
      var scale = MIN_SCALE;
      var translateX = scope.dimension/2,
          translateY = scope.dimension/2;

      var projection = d3.geo.azimuthal()
          .scale(scale*100)
          .origin([16.07, 43.3])
          .mode("orthographic")
          .translate([translateX, translateY]);

      var circle = d3.geo.greatCircle()
          .origin(projection.origin());

      var path = d3.geo.path()
          .projection(projection);

      var m0,
          o0;
      var rotate = [0,0];

      var zoom = d3.behavior.zoom()
          .scaleExtent([MIN_SCALE, MAX_SCALE])
          .on("zoomstart", function(){
            m0 = [d3.event.sourceEvent.pageX, d3.event.sourceEvent.pageY];
            var proj = rotate;
            o0 = [-proj[0],-proj[1]];

            d3.event.sourceEvent.stopPropagation();
          })
          .on("zoom", function() {
            if (m0) {
              var constant = (scale < 4) ? 4 : scale * 2;

              var m1 = [d3.event.sourceEvent.pageX, d3.event.sourceEvent.pageY],
                  o1 = [o0[0] + (m0[0] - m1[0]) / constant, o0[1] + (m1[1] - m0[1]) / constant];

            }

            rotate = [-o1[0], -o1[1]];
            // scale = (d3.event.scale >= MIN_SCALE) ? d3.event.scale : MIN_SCALE;
            if(d3.event.scale >= MIN_SCALE) {
              scale = d3.event.scale;
              if(d3.event.scale >= MAX_SCALE) {
                scale = MAX_SCALE;
              }
            } else {
              scale = MIN_SCALE;
            }

            projection.scale(scale*100);
            refresh(750);
          });

      var m0,
          o0, m1, o1;

      var svg = d3.select(element[0])
          .append("svg:svg")
          .attr("width", scope.dimension)
          .attr("height", scope.dimension)
          .on("mousedown", mousedown)
          .call(zoom);

      var color, data;

      color = d3.scale.linear()
        .domain(scope.valueRange)
        .range(scope.colorRange)
        .clamp(true);

      data = scope.worldData;
      data = d3.map(scope.worldData, function(d) { return d.countryCode; });



      feature = svg.selectAll("path")
          .data(worldTopoData.features)
          .enter().append("svg:path")
          .attr("d", clip)
          .attr("fill", scope.countryFillColor)
          .attr("stroke", scope.countryBorderColor);

      feature.append("svg:title")
          .text(function(d) { return d.properties.name; });

      feature.filter(function(d) { return data.has(d.id); })
          .style("fill", function(d) { var c= color(data.get(d.id).value); return c; });



      d3.select(window)
          .on("mousemove", mousemove)
          .on("mouseup", mouseup);





      function mousedown() {
        m0 = [d3.event.pageX, d3.event.pageY];
        o0 = projection.origin();
        d3.event.preventDefault();

      }

      function mousemove() {
        if (m0) {
          var constant = (scale < 4) ? 4 : scale * 2;

          var m1 = [d3.event.pageX, d3.event.pageY],
              o1 = [o0[0] + (m0[0] - m1[0]) / constant, o0[1] + (m1[1] - m0[1]) / constant];
          projection.origin(o1);
          circle.origin(o1);
          refresh();

        }
      }

      function mouseup() {
        if (m0) {
          mousemove();
          m0 = null;

        }
      }

      function refresh(duration) {
        (duration ? feature.transition().duration(duration) : feature).attr("d", clip);
      }

      function clip(d) {
        return path(circle.clip(d));
      }

    }
  };

  return directiveDefinitionObject;
});


angularMaps.directive('worldMap', function ($parse) {
  var directiveDefinitionObject = {
    //We restrict its use to an element
    //as usually  <globe> is semantically
    //more understandable
    restrict: 'E',
    //this is important,
    //we don't want to overwrite our directive declaration
    //in the HTML mark-up
    replace: false,
    //our data source would be json passed thru globe-data attribute
    //globe data value range would be an array of size 2 passed thru value-range attribute
    //color range would be an array of size 2 passed thru color-range attribute
    //dimension would be number passed thru dimension attribute
    scope: {
      worldData: '=worldData',
      valueRange: '=valueRange',
      colorRange: '=colorRange',
      mapWidth: '=mapWidth',
      countryFillColor: '=countryFillColor',
      countryBorderColor: '=countryBorderColor',
      descriptiveText: '=descriptiveText',
      countryData: '=countryData'
    },
    link: function (scope, element, attrs) {
      // Call From Json 
      var worldTopoData = scope.countryData; 
      var MIN_WIDTH=400;
      var MAX_WIDTH=1240;
      var MIN_SCALE=1,
          MAX_SCALE=8;
      if(scope.descriptiveText==null) {
        scope.descriptiveText = '';
      }
      if(scope.colorRange==null) {
        scope.colorRange = ["#F03B20", "#FFEDA0"];
      }
      if(scope.valueRange==null) {
        scope.valueRange = [0, 100];
      }
      if(scope.worldData==null) {
        scope.worldData = [];
      }
      if(scope.countryFillColor==null) {
        scope.countryFillColor = "#aaa";
      }
      if(scope.countryBorderColor==null) {
        scope.countryBorderColor = "#fff";
      }
      if(scope.mapWidth==null || scope.mapWidth < MIN_WIDTH) {
        scope.mapWidth = MIN_WIDTH;
      }

      scope.$watch(function() {
        return scope.worldData;
      }, function() {
          throttle()
      }, true);

      d3.select(window).on("resize", throttle);

      var zoom = d3.behavior.zoom()
          .scaleExtent([MIN_SCALE, MAX_SCALE])
          .on("zoom", move);

      var width = scope.mapWidth;
      var height = width / 2;

      var projection,path,svg,g;

      var tooltip = d3.select(element[0]).append("div").attr("class", "worldMapTooltip worldMapTooltipHidden");

      setup(width,height);

      function setup(width,height){
        projection = d3.geo.mercator()
                        .translate([0, 0])
                        .scale(width);
        

        path = d3.geo.path()
                  .projection(projection);

        svg = d3.select(element[0]).append("svg")
            .attr("viewBox", "0 0 " + width + " " + height )
        .attr("preserveAspectRatio", "xMinYMin")
            .append("g")
            .attr("transform", "translate(" + width / 2 + "," + height + ")")
            .call(zoom);
       
       // Map control
       var center = [width / 2, height / 2];

       d3.select('#zoom-in')
       .on("click", function(){
       	 var scale = zoom.scale(), extent = zoom.scaleExtent(), translate = zoom.translate();
                var x = translate[0], y = translate[1];
                var factor = 1.2;

                var target_scale = scale * factor;

                if (scale === extent[1]) {
                    return false;
                }
                var clamped_target_scale = Math.max(extent[0], Math.min(extent[1], target_scale));
                if (clamped_target_scale != target_scale) {
                    target_scale = clamped_target_scale;
                    factor = target_scale / scale;
                }
                x = (x - center[0]) * factor + center[0];
                y = (y - center[1]) * factor + center[1];

                zoom.scale(target_scale).translate([x, y]);

                g.transition().attr("transform", "translate(" + zoom.translate().join(",") + ") scale(" + zoom.scale() + ")");
                g.selectAll("path")
                        .attr("d", path.projection(projection));

                svg.selectAll("circle")
                        .transition()
                        .attr("transform", "translate(" + zoom.translate().join(",") + ") scale(" + zoom.scale() + ")")
                        .attr("r", 5 / zoom.scale());

                d3.select("#map-zoomer").node().value = zoom.scale();/**/ 
       });
	   d3.select('#zoom-out').on('click', function () {
                var scale = zoom.scale(), extent = zoom.scaleExtent(), translate = zoom.translate();
                var x = translate[0], y = translate[1];
                var factor = 1 / 1.2;

                var target_scale = scale * factor;

                if (scale === extent[0]) {
                    return false;
                }
                var clamped_target_scale = Math.max(extent[0], Math.min(extent[1], target_scale));
                if (clamped_target_scale != target_scale) {
                    target_scale = clamped_target_scale;
                    factor = target_scale / scale;
                }
                x = (x - center[0]) * factor + center[0];
                y = (y - center[1]) * factor + center[1];

                zoom.scale(target_scale).translate([x, y]);

                g.transition()
                        .attr("transform", "translate(" + zoom.translate().join(",") + ") scale(" + zoom.scale() + ")");
                g.selectAll("path")
                        .attr("d", path.projection(projection));

                svg.selectAll("circle")
                        .transition()
                        .attr("transform", "translate(" + zoom.translate().join(",") + ") scale(" + zoom.scale() + ")")
                        .attr("r", 5 / zoom.scale());
                d3.select("#map-zoomer").node().value = zoom.scale();
            });

            d3.select('#reset').on('click', function () {
                zoom.translate([0, 0]);
                zoom.scale(1);
                g.transition().attr("transform", "translate(" + zoom.translate().join(",") + ") scale(" + zoom.scale() + ")");
                g.selectAll("path")
                        .attr("d", path.projection(projection))

                svg.selectAll("circle")
                        .transition()
                        .attr("transform", "translate(" + zoom.translate().join(",") + ") scale(" + zoom.scale() + ")")
                        .transition()
                        .attr("r", 5 / zoom.scale());
                d3.select("#map-zoomer").node().value = zoom.scale();
            });

            d3.select('#map-zoomer').on("change", function () {
                var scale = zoom.scale(), extent = zoom.scaleExtent(), translate = zoom.translate();
                var x = translate[0], y = translate[1];
                var target_scale = +this.value;
                var factor = target_scale / scale;

                var clamped_target_scale = Math.max(extent[0], Math.min(extent[1], target_scale));
                if (clamped_target_scale != target_scale) {
                    target_scale = clamped_target_scale;
                    factor = target_scale / scale;
                }
                x = (x - center[0]) * factor + center[0];
                y = (y - center[1]) * factor + center[1];

                zoom.scale(target_scale).translate([x, y]);

                g.transition()
                        .attr("transform", "translate(" + zoom.translate().join(",") + ") scale(" + zoom.scale() + ")");
                g.selectAll("path")
                        .attr("d", path.projection(projection));

                svg.selectAll("circle")
                        .transition()
                        .attr("transform", "translate(" + zoom.translate().join(",") + ") scale(" + zoom.scale() + ")")
                        .attr("r", 5 / zoom.scale());
            });


        g = svg.append("g");

    }

    var color, data;

    color = getColor();

    function getColor() {
    	return d3.scale.linear()
	    	.domain(scope.valueRange)
	      .range(scope.colorRange)
	      .clamp(true);
    }

    data = d3.map(scope.worldData, function(d) { return d.countryCode; });

    var topo = worldTopoData.features;

    draw(topo);

    function draw(topo) {
      var country = g.selectAll(".worldMapMycountry").data(topo);

      country.enter().insert("path")
          .attr("class", "worldMapMycountry")
          .attr("d", path)
          .attr("id", function(d,i) { return d.id; })
          .attr("title", function(d,i) { return d.properties.name; })
          .style("fill", function(d, i) {
            var c=scope.countryFillColor;
            if(data) {
              var cData =data.get(d.id);
              if(cData) {
                c = color(cData.value);
              }
            }
            return c;
          })
          .attr("stroke", scope.countryBorderColor);

          

      var offsetL = element[0].offsetLeft+(width/2);
      var offsetT = element[0].offsetTop+(height/2)+50;

      //tooltips
      var title;
      country
      .on("mousemove", function(d,i) {
          var mouse = d3.mouse(svg.node()).map( function(d) { return parseInt(d); } );

          var tooltipValue=d.properties.name;
          if(d.properties.zoneName)
           scope.date = new moment().tz(d.properties.zoneName).format('LLLL');
  
   

          if(data.get(d.id)) {
            tooltipValue += ' '+ scope.descriptiveText + ' : ' + data.get(d.id).value;
 
          }
          tooltip
            .classed("worldMapTooltipHidden", false)
            //.attr("style", "left:"+(mouse[0]+offsetL)+"px;top:"+(mouse[1]+offsetT)+"px")
            .attr("style","width:80%;bottom:0px;text-align:center")
            .html(tooltipValue + ' / '+ scope.date)
        })
      .on("mouseout",  function(d,i) {
          tooltip.classed("worldMapTooltipHidden", true)
        });

    }

    function redraw() {
      width = scope.mapWidth;
      height = width / 2;
      setup(width,height);
      draw(topo);
    }

    function move() {
      var t = d3.event.translate;
      var s = d3.event.scale;

      var h = height / 3;

      t[0] = Math.min(width / 2 * (s - 1), Math.max(width / 2 * (1 - s), t[0]));
      t[1] = Math.min(height / 2 * (s - 1) + h * s, Math.max(height / 2 * (1 - s) - h * s, t[1]));

      zoom.translate(t);
      g.style("stroke-width", 1 / s).attr("transform", "translate(" + t + ")scale(" + s + ")");

    }

    var throttleTimer;
    function throttle() {
    	d3.select('svg').remove();
      window.clearTimeout(throttleTimer);
      throttleTimer = window.setTimeout(function() {
    		d3.select('svg').remove();
      	color = getColor();
	    	data = d3.map(scope.worldData, function(d) { return d.countryCode; });
        redraw();
      }, 200);
    }

    }
  };

  return directiveDefinitionObject;
});