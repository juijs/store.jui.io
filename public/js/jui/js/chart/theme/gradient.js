jui.define("chart.theme.gradient", [], function() {
    var themeColors = [
        "linear(top) #9694e0,0.9 #7977C2",
        "linear(top) #a1d6fc,0.9 #7BBAE7",
        "linear(top) #ffd556,0.9 #ffc000",
        "linear(top) #ff9d46,0.9 #ff7800",
        "linear(top) #9cd37a,0.9 #87bb66",
        "linear(top) #3bb9b2,0.9 #1da8a0",
        "linear(top) #b3b3b3,0.9 #929292",
        "linear(top) #67717f,0.9 #555d69",
        "linear(top) #16b5f6,0.9 #0298d5",
        "linear(top) #ff686c,0.9 #fa5559",
        "linear(top) #fbbbb1,0.9 #f5a397",
        "linear(top) #3aedcf,0.9 #06d9b6",
        "linear(top) #d8c2e7,0.9 #c6a9d9",
        "linear(top) #8a87ff,0.9 #6e6afc",
        "linear(top) #eef18c,0.9 #e3e768",
        "linear(top) #ee52a2,0.9 #df328b",
        "linear(top) #b6e5f4,0.9 #96d7eb",
        "linear(top) #93aec8,0.9 #839cb5",
        "linear(top) #b76fef,0.9 #9228e4"
    ];

    return {
        backgroundColor : "white",
        fontFamily : "arial,Tahoma,verdana",
        colors : themeColors,

        // Grid styles
        gridXFontSize : 11,
        gridYFontSize : 11,
        gridCFontSize : 11,
        gridXFontColor : "#666",
        gridYFontColor : "#666",
        gridCFontColor : "#666",
        gridXFontWeight : "normal",
        gridYFontWeight : "normal",
        gridCFontWeight : "normal",
        gridXAxisBorderColor : "#efefef",
        gridYAxisBorderColor : "#efefef",
        gridXAxisBorderWidth : 2,
        gridYAxisBorderWidth : 2,

        gridActiveFontColor : "#ff7800",
        gridActiveBorderColor : "#ff7800",
        gridPatternColor : "#ababab",
        gridPatternOpacity : 0.1,
        gridBorderColor : "#efefef",
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
        barFontSize : 11,
        barFontColor : "#333",
        barBorderColor : "none",
        barBorderWidth : 0,
        barBorderOpacity : 0,
        barBorderRadius : 3,
        barActiveBackgroundColor : "linear(top) #3aedcf,0.9 #06d9b6",
        barPointBorderColor : "white",
        barDisableBackgroundOpacity : 0.4,
        gaugeBackgroundColor : "#ececec",
        gaugeArrowColor : "#666666",
        gaugeFontColor : "#666666",
        gaugeFontSize : 20,
        gaugeFontWeight : "bold",
        gaugeTitleFontSize : 12,
        gaugeTitleFontWeight : "normal",
        gaugeTitleFontColor : "#333",
        bargaugeBackgroundColor : "#ececec",
        bargaugeFontSize : 11,
        bargaugeFontColor : "#333333",
        pieBorderColor : "white",
        pieBorderWidth : 1,
        pieOuterFontSize : 11,
        pieOuterLineColor : "#a9a9a9",
        pieOuterLineSize : 8,
        pieOuterLineRate : 1.3,
        pieActiveDistance : 5,
        areaBackgroundOpacity : 0.4,
        areaSplitBackgroundColor : "linear(top) #b3b3b3,0.9 #929292",
        bubbleBackgroundOpacity : 0.5,
        bubbleBorderWidth : 1,
        candlestickBorderColor : "black",
        candlestickBackgroundColor : "linear(top) white",
        candlestickInvertBorderColor : "red",
        candlestickInvertBackgroundColor : "linear(top) red",
        ohlcBorderColor : "#14be9d",
        ohlcInvertBorderColor : "#ff4848",
        ohlcBorderRadius : 5,
        lineBorderWidth : 2,
        lineBorderDashArray : "none",
        lineDisableBorderOpacity : 0.3,
        linePointBorderColor : "white",
        lineSplitBorderColor : null,
        lineSplitBorderOpacity : 0.5,
        pathBackgroundOpacity : 0.5,
        pathBorderWidth : 1,
        scatterBorderColor : "white",
        scatterBorderWidth : 2,
        scatterHoverColor : "white",
        waterfallBackgroundColor : "linear(top) #9cd37a,0.9 #87bb66",
        waterfallInvertBackgroundColor : "linear(top) #ff9d46,0.9 #ff7800",
        waterfallEdgeBackgroundColor : "linear(top) #a1d6fc,0.9 #7BBAE7",
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
        topologyNodeFontColor : "white",
        topologyNodeTitleFontSize : 11,
        topologyNodeTitleFontColor : "#333",
        topologyEdgeColor : "#b2b2b2",
        topologyActiveEdgeColor : "#905ed1",
        topologyEdgeFontSize : 10,
        topologyEdgeFontColor : "#666",
        topologyEdgePointRadius : 3,
        topologyTooltipBackgroundColor : "white",
        topologyTooltipBorderColor : "#ccc",
        topologyTooltipFontSize : 11,
        topologyTooltipFontColor : "#333",

        // widget styles
        titleFontColor : "#333",
        titleFontSize : 13,
        titleFontWeight : "normal",
        legendFontColor : "#666",
        legendFontSize : 12,
        legendIconRadius : 6,
        tooltipFontColor : "#333",
        tooltipFontSize : 12,
        tooltipBackgroundColor : "white",
        tooltipBorderColor : "none",
        tooltipBorderWidth : 2,
        tooltipBackgroundOpacity : 1,
        scrollBackgroundSize : 7,
        scrollBackgroundColor : "#dcdcdc",
        scrollThumbBackgroundColor : "#b2b2b2",
        scrollThumbBorderColor : "#9f9fa4",
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
        crossBalloonFontColor : "white",
        crossBalloonBackgroundColor : "black",
        crossBalloonBackgroundOpacity : 0.8,
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
        mapBubbleFontColor : "white",
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