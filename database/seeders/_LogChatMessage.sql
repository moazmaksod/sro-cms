DROP TABLE IF EXISTS [dbo].[_LogChatMessage];

CREATE TABLE [dbo].[_LogChatMessage](
	[ShardName] [varchar](64) NOT NULL,
	[EventTime] [datetime] NOT NULL,
	[CharName] [nvarchar](64) NOT NULL,
	[TargetName] [nvarchar](64) NOT NULL,
	[ContinentName] [varchar](128) NOT NULL,
	[Comment] [nvarchar](128) NULL
) ON [PRIMARY]
