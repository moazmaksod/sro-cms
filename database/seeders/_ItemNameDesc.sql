DROP TABLE IF EXISTS [dbo].[_Rigid_ItemNameDesc]

CREATE TABLE [dbo].[_Rigid_ItemNameDesc](
    [Service] [int] NOT NULL,
    [ID] [int] NOT NULL,
    [StrID] [varchar](255) NOT NULL,
    [KOR] [varchar](max) NOT NULL,
    [UNK0] [varchar](max) NOT NULL,
    [UNK1] [varchar](max) NOT NULL,
    [UNK2] [varchar](max) NOT NULL,
    [UNK3] [varchar](max) NOT NULL,
    [VNM] [varchar](max) NOT NULL,
    [ENG] [varchar](max) NOT NULL,
    [UNK4] [varchar](max) NOT NULL,
    [UNK5] [varchar](max) NOT NULL,
    [UNK6] [varchar](max) NOT NULL,
    [TUR] [varchar](max) NOT NULL,
    [ARA] [varchar](max) NOT NULL,
    [ESP] [varchar](max) NOT NULL,
    [GER] [varchar](max) NOT NULL
    ) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
