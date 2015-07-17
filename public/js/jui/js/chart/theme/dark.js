jui.define("chart.theme.dark", [], function() {
    var themeColors = [
        "#12f2e8",
        "#26f67c",
        "#e9f819",
        "#b78bf9",
        "#f94590",
        "#8bccf9",
        "#9228e4",
        "#06d9b6",
        "#fc6d65",
        "#f199ff",
        "#c8f21d",
        "#16a6e5",
        "#00ba60",
        "#91f2a1",
        "#fc9765",
        "#f21d4f"
    ];

    return {
        fontFamily : "arial,Tahoma,verdana",
    	backgroundColor : "#222222",
        colors : themeColors,

        // Grid styles
        gridXFontSize : 11,
        gridYFontSize : 11,
        gridCFontSize : 11,
        gridXFontColor : "#868686",
        gridYFontColor : "#868686",
        gridCFontColor : "#868686",
        gridXFontWeight : "normal",
        gridYFontWeight : "normal",
        gridCFontWeight : "normal",
        gridXAxisBorderColor : "#464646",
        gridYAxisBorderColor : "#464646",
        gridXAxisBorderWidth : 2,
        gridYAxisBorderWidth : 2,

    	gridActiveFontColor : "#ff762d",
        gridActiveBorderColor : "#ff7800",
        gridPatternColor : "#ababab",
        gridPatternOpacity : 0.1,
        gridBorderColor : "#464646",
        gridBorderWidth : 1,
        gridBorderDashArray : "none",
        gridBorderOpacity : 1,
        gridTickBorderSize : 3,
        gridTickBorderWidth : 1.5,
        gridTickPadding : 5,

        // Brush styles
        tooltipPointRadius : 5, // common
        tooltipPointBorderWidth : 1, // common
        tooltipPointFontWeight : "bold", // common
        tooltipPointFontSize : 11,
        barFontSize : 11,
        barFontColor : "#868686",
        barBorderColor : "none",
        barBorderWidth : 0,
        barBorderOpacity : 0,
        barBorderRadius : 3,
        barActiveBackgroundColor : "#fc6d65",
        barPointBorderColor : "white",
        barDisableBackgroundOpacity : 0.4,
    	gaugeBackgroundColor : "#3e3e3e",
        gaugeArrowColor : "#a6a6a6",
        gaugeFontColor : "#c5c5c5",
        gaugeFontSize : 20,
        gaugeFontWeight : "bold",
        gaugeTitleFontSize : 12,
        gaugeTitleFontWeight : "normal",
        gaugeTitleFontColor : "#c5c5c5",
        bargaugeBackgroundColor : "#3e3e3e",
        bargaugeFontSize : 11,
        bargaugeFontColor : "#c5c5c5",
    	pieBorderColor : "#232323",
        pieBorderWidth : 1,
        pieOuterFontSize : 11,
        pieOuterLineColor : "#a9a9a9",
        pieOuterLineSize : 8,
        pieOuterLineRate : 1.3,
        pieActiveDistance : 5,
        areaBackgroundOpacity : 0.5,
        areaSplitBackgroundColor : "#ebebeb",
        bubbleBackgroundOpacity : 0.5,
        bubbleBorderWidth : 1,
        candlestickBorderColor : "#14be9d",
        candlestickBackgroundColor : "#14be9d",
        candlestickInvertBorderColor : "#ff4848",
        candlestickInvertBackgroundColor : "#ff4848",
        ohlcBorderColor : "#14be9d",
        ohlcInvertBorderColor : "#ff4848",
        ohlcBorderRadius : 5,
        lineBorderWidth : 2,
        lineBorderDashArray : "none",
        lineDisableBorderOpacity : 0.3,
        linePointBorderColor : "white",
        lineSplitBorderColor : null,
        lineSplitBorderOpacity : 0.5,
        pathBackgroundOpacity : 0.2,
        pathBorderWidth : 1,
        scatterBorderColor : "none",
        scatterBorderWidth : 1,
        scatterHoverColor : "#222222",
        waterfallBackgroundColor : "#26f67c",
        waterfallInvertBackgroundColor : "#f94590",
        waterfallEdgeBackgroundColor : "#8bccf9",
        waterfallLineColor : "#a9a9a9",
        waterfallLineDashArray : "0.9",
        focusBorderColor : "#FF7800",
        focusBorderWidth : 1,
        focusBackgroundColor : "#FF7800",
        focusBackgroundOpacity : 0.1,
        pinFontColor : "#FF7800",
        pinFontSize : 10,
        pinBorderColor : "#FF7800",
        pinBorderWidth : 0.7,

        topologyNodeRadius : 12.5,
        topologyNodeFontSize : 14,
        topologyNodeFontColor : "#c5c5c5",
        topologyNodeTitleFontSize : 11,
        topologyNodeTitleFontColor : "#c5c5c5",
        topologyEdgeColor : "#b2b2b2",
        topologyActiveEdgeColor : "#905ed1",
        topologyEdgeFontSize : 10,
        topologyEdgeFontColor : "#c5c5c5",
        topologyEdgePointRadius : 3,
        topologyTooltipBackgroundColor : "#222222",
        topologyTooltipBorderColor : "#ccc",
        topologyTooltipFontSize : 11,
        topologyTooltipFontColor : "#c5c5c5",

        // widget styles
        titleFontColor : "#ffffff",
        titleFontSize : 14,
        titleFontWeight : "normal",
        legendFontColor : "#ffffff",
        legendFontSize : 11,
        legendIconRadius : 5.5,
        tooltipFontColor : "#333333",
        tooltipFontSize : 12,
        tooltipBackgroundColor : "white",
        tooltipBorderColor : "white",
        tooltipBorderWidth : 2,
        tooltipBackgroundOpacity : 1,
        scrollBackgroundSize : 7,
        scrollBackgroundColor : "#3e3e3e",
        scrollThumbBackgroundColor : "#666666",
        scrollThumbBorderColor : "#686868",
        zoomBackgroundColor : "red",
        zoomFocusColor : "gray",
        zoomScrollBackgroundSize : 50,
        zoomScrollBackgroundColor : "#000",
        zoomScrollFocusColor : "#fff",
        zoomScrollBrushColor : "#000",
        crossBorderColor : "#a9a9a9",
        crossBorderWidth : 1,
        crossBorderOpacity : 0.8,
        crossBalloonFontSize : 11,
        crossBalloonFontColor : "#333",
        crossBalloonBackgroundColor : "white",
        crossBalloonBackgroundOpacity : 1,
        dragSelectBackgroundColor : "#7BBAE7",
        dragSelectBackgroundOpacity : 0.3,
        dragSelectBorderColor : "#7BBAE7",
        dragSelectBorderWidth : 1,

        // Map Common
        mapPathBackgroundColor : "#67B7DC",
        mapPathBackgroundOpacity : 1,
        mapPathBorderColor : "white",
        mapPathBorderWidth : 1,
        mapPathBorderOpacity : 1,
        // Map Brushes
        mapBubbleBackgroundOpacity : 0.5,
        mapBubbleBorderWidth : 1,
        mapBubbleFontSize : 11,
        mapBubbleFontColor : "#868686",
        mapSelectorHoverColor : "#5a73db",
        mapSelectorActiveColor : "#CC0000",
        mapFlightRouteAirportSmallColor : "#CC0000",
        mapFlightRouteAirportLargeColor : "#000",
        mapFlightRouteAirportBorderWidth : 2,
        mapFlightRouteAirportRadius : 8,
        mapFlightRouteLineColor : "red",
        mapFlightRouteLineWidth : 1,
        mapWeatherBackgroundColor : "white",
        mapWeatherBorderColor : "#a9a9a9",
        mapWeatherFontSize : 11,
        mapWeatherTitleFontColor : "#666",
        mapWeatherInfoFontColor : "red",
        mapCompareBubbleMaxLineColor : "#fff",
        mapCompareBubbleMaxLineDashArray : "2,2",
        mapCompareBubbleMaxBorderColor : "#fff",
        mapCompareBubbleMaxFontSize : 36,
        mapCompareBubbleMaxFontColor : "#fff",
        mapCompareBubbleMinBorderColor : "#ffff00",
        mapCompareBubbleMinFontSize : 24,
        mapCompareBubbleMinFontColor : "#000",
        // Map Widgets
        mapControlButtonColor : "#3994e2",
        mapControlScrollColor : "#000",
        mapControlScrollLineColor : "#fff"
    }
});